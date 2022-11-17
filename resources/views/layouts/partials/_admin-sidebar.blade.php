<li class="nav-item"><a class="nav-link {{(request()->is('admin')) ? 'active' : ''}}" href="{{route('admin.index')}}"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
<li class="nav-item"><a class="nav-link {{(request()->is('admin/majors*')) ? 'active' : ''}}" href="{{route('admin.majors.index')}}"><i class="fa fa-university"></i><span>Major</span></a></li>
<li class="nav-item"><a class="nav-link" href="profile.html"><i class="fas fa-user"></i><span>Lecturer &amp; Student</span></a></li>
<li class="nav-item"><a class="nav-link" href="login.html"><i class="fa fa-book"></i><span>Subject</span></a></li>
