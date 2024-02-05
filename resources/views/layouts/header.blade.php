<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>

    </ul>


    <ul class="navbar-nav ml-auto">
        @php
            $getAllChatUserCount = App\Models\ChatModel::getAllChatUserCount();
        @endphp


        <li class="nav-item">
            <a class="nav-link" href="{{ route('chat.index') }}">
                <i class="far fa-comments"></i>
                <span
                    class="badge badge-danger navbar-badge">{{ !empty($getAllChatUserCount) ? $getAllChatUserCount : '' }}</span>
            </a>

        </li>

    </ul>
</nav>



<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <a href="javascript:;" class="brand-link">
        <img src="{{ url('dist/img/Logo.jpg') }}" alt="Logo" class="brand-image img-circle elevation-3 ml-0"
            style="opacity: 1">
        <span class="brand-text font-weight-bold">PKT Education Center</span>
    </a>


    <div class="sidebar">

        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/uploads/' . Auth::user()->profile_pic) }}" class="img-circle elevation-2"
                    alt="User Image" style="width: 40px; height: 40px; border-radius: 40px;">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>


        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                @if (Auth::user()->user_type == 1)
                    <li class="nav-item">
                        <a href="{{ url('admin/dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <!-- Users Dropdown -->
                    <li class="nav-item has-treeview @if (Request::segment(2) == 'admin' ||
                            Request::segment(2) == 'teacher' ||
                            Request::segment(2) == 'student' ||
                            Request::segment(2) == 'parent') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <!-- Admin -->
                            <li class="nav-item">
                                <a href="{{ route('admin.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                                    <i class="fas fa-user-tie nav-icon"></i>
                                    <p>Admin</p>
                                </a>
                            </li>

                            <!-- Teacher -->
                            <li class="nav-item">
                                <a href="{{ route('admin_teacher.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                                    <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                    <p>Teacher</p>
                                </a>
                            </li>

                            <!-- Student -->
                            <li class="nav-item">
                                <a href="{{ route('admin_student.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'student') active @endif">
                                    <i class="fas fa-user-graduate nav-icon"></i>
                                    <p>Student</p>
                                </a>
                            </li>

                            <!-- Parent -->
                            <li class="nav-item">
                                <a href="{{ route('admin_parent.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'parent') active @endif">
                                    <i class="fas fa-user-friends nav-icon"></i>
                                    <p>Parent</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Academics --}}
                    <li class="nav-item has-treeview @if (Request::segment(2) == 'class' ||
                            Request::segment(2) == 'subject' ||
                            Request::segment(2) == 'assign_subject' ||
                            Request::segment(2) == 'assign_class_teacher') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Academics
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('class.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'class') active @endif">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>
                                        Class
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('subject.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'subject') active @endif">
                                    <i class="nav-icon fas fa-pencil-alt"></i>
                                    <p>
                                        Subject
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('assign_subject.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_subject') active @endif">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Assign Subject
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('assign_class_teacher.index') }}"
                                    class="nav-link @if (Request::segment(2) == 'assign_class_teacher') active @endif">
                                    <i class="nav-icon fas fa-chart-bar"></i>
                                    <p>
                                        Assign Class Teacher
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item has-treeview @if (Request::segment(2) == 'examinations' ||
                            Request::segment(2) == 'examinations' ||
                            Request::segment(2) == 'examinations' ||
                            Request::segment(2) == 'examinations') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-graduation-cap"></i>
                            <p>
                                Examinations
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">

                            <li class="nav-item">
                                <a href="{{ route('exam.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam') active @endif">
                                    <i class="nav-icon far fas fa-clipboard-check"></i>
                                    <p>
                                        Exam List
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('exam_schedule.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'exam_schedule') active @endif">
                                    <i class="nav-icon far fas fa-calendar-alt"></i>
                                    <p>
                                        Exam Schedule
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('exam_marks.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'marks_register') active @endif">
                                    <i class="nav-icon far fas fa-poll"></i>
                                    <p>
                                        Marks Register
                                    </p>
                                </a>
                            </li>
                        </ul>

                    <li class="nav-item has-treeview @if (Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                Student Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('attendance.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'attendance') active @endif">
                                    <i class="nav-icon far fas fa-check-circle"></i>
                                    <p>
                                        Attendance
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('attendance_report.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'attendance_report') active @endif">
                                    <i class="nav-icon far fas fa-file-alt"></i>
                                    <p>
                                        Attendance Report
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('homework.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="nav-icon far fas fa-tasks"></i>
                                    <p>
                                        Homework
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item has-treeview @if (Request::segment(2) == 'fees_collection' ||
                            Request::segment(2) == 'fees_collection' ||
                            Request::segment(2) == 'fees_collection' ||
                            Request::segment(2) == 'fees_collection') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon far fa-money-bill-alt"></i>
                            <p>
                                Fees Collection
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('fees_collection.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'collect_fees') active @endif">
                                    <i class="nav-icon far fa-credit-card"></i>
                                    <p>
                                        Collect Fees
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin_settings.index') }}"
                            class="nav-link @if (Request::segment(2) == 'profile') active @endif">
                            <i class="nav-icon far fas fa-cogs"></i>
                            <p>
                                Account Settings
                            </p>
                        </a>
                    </li>



                    {{-- Teacher side --}}
                @elseif(Auth::user()->user_type == 2)
                    <li class="nav-item">
                        <a href="{{ route('teacher.dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.my_class_subject') }}"
                            class="nav-link @if (Request::segment(2) == 'my_class_subject') active @endif">
                            <i class="nav-icon fas fa-chalkboard"></i>
                            <p>
                                My Class & Subjects
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.myStudent') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon fas fa-chalkboard"></i>
                            <p>
                                My Students
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher.my_exam_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>
                                My Exam Timetable
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('exam_marks_teacher.index') }}"
                            class="nav-link @if (Request::segment(2) == 'marks_register') active @endif">
                            <i class="nav-icon far fas fa-poll"></i>
                            <p>
                                Marks Register
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin_teacher_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Admin
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('parent_teacher_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'parent') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Parent
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview @if (Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management' ||
                            Request::segment(2) == 'student_management') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                Student Management
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('attendance_teacher.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'attendance') active @endif">
                                    <i class="nav-icon far fas fa-check-circle"></i>
                                    <p>
                                        Attendance
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('attendance_report_teacher.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'attendance_report') active @endif">
                                    <i class="nav-icon far fas fa-file-alt"></i>
                                    <p>
                                        Attendance Report
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('homework_teacher.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'homework') active @endif">
                                    <i class="nav-icon far fas fa-tasks"></i>
                                    <p>
                                        Homework
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher_settings.index') }}"
                            class="nav-link @if (Request::segment(2) == 'profile') active @endif">
                            <i class="nav-icon far fas fa-cogs"></i>
                            <p>
                                Account Settings
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 3)
                    <li class="nav-item">
                        <a href="{{ route('student.dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student.mySubject') }}"
                            class="nav-link @if (Request::segment(2) == 'my_subject') active @endif">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                My Subjects
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student.my_exam_timetable') }}"
                            class="nav-link @if (Request::segment(2) == 'my_exam_timetable') active @endif">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>
                                My Exam Timetable
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin_student_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Admin
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher_student_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Teacher
                            </p>
                        </a>
                    </li>

                    <li class="nav-item has-treeview @if (Request::segment(2) == 'my_academic_info' ||
                            Request::segment(2) == 'my_academic_info' ||
                            Request::segment(2) == 'my_academic_info' ||
                            Request::segment(2) == 'my_academic_info') menu-open @endif">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-briefcase"></i>
                            <p>
                                My Academic Info
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('attendance_report_student.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'attendance_report') active @endif">
                                    <i class="nav-icon far fas fa-check-circle"></i>
                                    <p>
                                        My Attendance
                                    </p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('homework_student.index') }}"
                                    class="nav-link @if (Request::segment(3) == 'my_homework') active @endif">
                                    <i class="nav-icon far fas fa-tasks"></i>
                                    <p>
                                        My Homework
                                    </p>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('student_settings.index') }}"
                            class="nav-link @if (Request::segment(2) == 'profile') active @endif">
                            <i class="nav-icon far fas fa-cogs"></i>
                            <p>
                                Account Settings
                            </p>
                        </a>
                    </li>
                @elseif(Auth::user()->user_type == 4)
                    <li class="nav-item">
                        <a href="{{ route('parent.dashboard') }}"
                            class="nav-link @if (Request::segment(2) == 'dashboard') active @endif">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('parent.my_student') }}"
                            class="nav-link @if (Request::segment(2) == 'my_student') active @endif">
                            <i class="nav-icon fas fa-child"></i>
                            <p>
                                My Student Information
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin_parent_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'admin') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Admin
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('teacher_parent_side.index') }}"
                            class="nav-link @if (Request::segment(2) == 'teacher') active @endif">
                            <i class="nav-icon fas fa-comments"></i>
                            <p>
                                Contact Teacher
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('parent_settings.index') }}"
                            class="nav-link @if (Request::segment(2) == 'profile') active @endif">
                            <i class="nav-icon far fas fa-cogs"></i>
                            <p>
                                Account Settings
                            </p>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{ url('logout') }}" class="nav-link">
                        <i class="nav-icon far fa-user"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>

            </ul>
        </nav>

    </div>

</aside>
