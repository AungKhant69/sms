<?php

namespace App\Http\Controllers;

use Exception;
use Stripe\Stripe;
use App\Models\User;
use App\Helper\FormHelper;
use Illuminate\Http\Request;
use App\Mail\StripePaymentSuccess;
use App\Models\AddStudentFeesModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class FeesCollectionController extends Controller
{
    public $pagination = '';
    public $data = [];
    public function __construct()
    {
        $config = FormHelper::getConfig();
        $this->pagination = $config['paginate'];
        $this->data = [
            'header_title' => 'Fees Collection',
            'getRecord' => [],
        ];
    }

    public function collect_fees(Request $request)
    {
        $this->data['getRecord'] = $this->collectStudentFees($request);

        return view('admin.fees_collection.collect_fees')->with([
            'data' => $this->data,
        ]);
    }

    private function collectStudentFees(Request $request)
    {
        $query =  User::where('users.user_type', '=', 3)
            ->with('parent', 'classData');

        if ($request->name != '') {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->email != '') {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        $paginator = $query->orderBy('id', 'desc')->paginate($this->pagination);

        $paginator->appends([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
        ]);
        return $paginator;
    }

    public function add_fees($student_id)
    {
        $this->data['getFees'] = $this->getFees($student_id);
        $this->data['getStudent'] = $this->getSingleClass($student_id);
        $class_id = $this->getSingleClass($student_id);

        $this->data['header_title'] = 'Add Fees';
        $this->data['paid_amount'] = $this->getPaidAmount($student_id, $class_id);

        return view('admin.fees_collection.add_fees')->with([
            'data' => $this->data,
        ]);
    }

    public function add_fees_parent_side($student_id)
    {
        $this->data['getFees'] = $this->getFees($student_id);
        $this->data['getStudent'] = $this->getSingleClass($student_id);
        $class_id = $this->getSingleClass($student_id);

        $this->data['header_title'] = 'Add Fees';
        $this->data['paid_amount'] = $this->getPaidAmount($student_id, $class_id);

        return view('parent.add_fees')->with([
            'data' => $this->data,
        ]);
    }

    public function store_fees($student_id, Request $request)
    {
        try {
            $getStudent = $this->getSingleClass($student_id);

            $paid_amount = $this->getPaidAmount($student_id, $getStudent->class_id);

            if (!empty($request->amount)) {
                $remainingAmount = $request->total_amount - $paid_amount;

                if ($remainingAmount >= $request->amount) {
                    $remainingAmount_user = $remainingAmount - $request->amount;

                    $payment = new AddStudentFeesModel;
                    $payment->student_id  = $student_id;
                    $payment->class_id = $getStudent->class_id;
                    $payment->total_amount = $remainingAmount;
                    $payment->paid_amount = $request->amount;
                    $payment->remaining_amount = $remainingAmount_user;
                    $payment->payment_type = $request->payment_type;
                    $payment->message = $request->message;
                    $payment->created_by = Auth::user()->id;

                    if ($request->payment_type == 'Stripe') {
                        $setPublicKey = config('services.stripe.key');
                        $setApiKey = config('services.stripe.secret');

                        \Stripe\Stripe::setApiKey(config('stripe.sk'));
                        $finalPrice = intval($request->amount * 100);

                        $session = \Stripe\Checkout\Session::create([
                            'customer_email' => Auth::user()->email,
                            'payment_method_types' => ['card'],
                            'line_items'  => [
                                [
                                    'price_data' => [
                                        'currency'     => 'USD',
                                        'product_data' => [
                                            "name" => 'Student Fees',
                                        ],
                                        'unit_amount'  => $finalPrice,
                                    ],
                                    'quantity'   => 1,
                                ],
                            ],
                            'mode'        => 'payment',
                            'success_url' => route('stripe.payment_success'),
                            'cancel_url' => route('stripe.payment_error'),
                        ]);

                        $payment->stripe_session_id = $session['id'];
                        $payment->save();

                        $this->data['session_id'] = $session->id;
                        Session::put('stripe_session_id', $session->id);
                        $this->data['setPublicKey'] = config('stripe.pk');

                        $admins = User::where('user_type', 1)->get();

                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new StripePaymentSuccess($payment));
                        }

                        return view('stripe_charge')->with([
                            'session_id' => $this->data['session_id'],
                            'setPublicKey' => $this->data['setPublicKey'],
                            'data' => $this->data,
                        ]);
                    }
                    if ($request->payment_type == 'Cash') {
                        return redirect()->back()->with('success', 'Fees Successfully Added.');
                    }
                } else {
                    return redirect()->back()->with('error', 'Input amount is greater than remaining amount.');
                }
            } else {
                return redirect()->back()->with('error', 'You need to add at least 1$.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function store_fees_parent_side($student_id, Request $request)
    {
        try {
            $getStudent = $this->getSingleClass($student_id);

            $paid_amount = $this->getPaidAmount($student_id, $getStudent->class_id);

            if (!empty($request->amount)) {
                $remainingAmount = $request->total_amount - $paid_amount;

                if ($remainingAmount >= $request->amount) {
                    $remainingAmount_user = $remainingAmount - $request->amount;
                    $paymentId = mt_rand(100000, 999999);

                    $payment = new AddStudentFeesModel;
                    $payment->student_id  = $student_id;
                    $payment->class_id = $getStudent->class_id;
                    $payment->total_amount = $remainingAmount;
                    $payment->paid_amount = $request->amount;
                    $payment->remaining_amount = $remainingAmount_user;
                    $payment->payment_type = $request->payment_type;
                    $payment->payment_id = $paymentId;
                    $payment->message = $request->message;
                    $payment->created_by = Auth::user()->id;

                    if ($request->payment_type == 'Stripe') {
                        $setPublicKey = config('services.stripe.key');
                        $setApiKey = config('services.stripe.secret');

                        \Stripe\Stripe::setApiKey(config('stripe.sk'));
                        $finalPrice = intval($request->amount * 100);

                        $session = \Stripe\Checkout\Session::create([
                            'customer_email' => Auth::user()->email,
                            'payment_method_types' => ['card'],
                            'line_items'  => [
                                [
                                    'price_data' => [
                                        'currency'     => 'USD',
                                        'product_data' => [
                                            "name" => 'Student Fees',
                                        ],
                                        'unit_amount'  => $finalPrice,
                                    ],
                                    'quantity'   => 1,
                                ],
                            ],
                            'mode'        => 'payment',
                            'success_url' => route('stripe.payment_success_parent_side'),
                            'cancel_url' => route('stripe.payment_error_parent_side'),
                        ]);

                        $payment->stripe_session_id = $session['id'];
                        $payment->save();

                        $this->data['session_id'] = $session->id; // Set $session_id here
                        Session::put('stripe_session_id', $session->id);
                        $this->data['setPublicKey'] = config('stripe.pk');

                        $admins = User::where('user_type', 1)->get();

                        foreach ($admins as $admin) {
                            Mail::to($admin->email)->send(new StripePaymentSuccess($payment));
                        }

                        return view('stripe_charge')->with([
                            'session_id' => $this->data['session_id'],
                            'setPublicKey' => $this->data['setPublicKey'],
                            'data' => $this->data,
                        ]);
                    }

                } else {
                    return redirect()->back()->with('error', 'You cannot pay more than remaining amount.');
                }
            } else {
                return redirect()->back()->with('error', 'You need to add at least 1$.');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    private function getSingleClass($id)
    {
        return User::where('users.id', '=', $id)->with('classData', 'amount')
            ->first();
    }

    private function getFees($student_id)
    {
        return AddStudentFeesModel::where('student_id', $student_id)
            ->with('classData', 'student', 'createdBy', 'updatedBy')->get();
    }

    private function getPaidAmount($student_id, $class_id)
    {
        return AddStudentFeesModel::where('student_id', $student_id)
            ->where('class_id', $class_id)
            ->sum('add_student_fees.paid_amount');
    }

    public function payment_error()
    {
        return redirect('admin/fees_collection/collect_fees')->with('error', 'Payment Error');
    }

    public function payment_error_parent_side()
    {
        return redirect('parent/my_student')->with('error', 'Payment Error');
    }

    public function payment_success(Request $request)
    {
        return redirect()->back()->with('success', 'Payment success');
    }
}
