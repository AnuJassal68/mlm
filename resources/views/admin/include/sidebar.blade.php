

 

      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
          <li class="nav-item">
            <a class="nav-link" href="{{('/admindashboard')}}">
              <i class="mdi mdi-grid-large menu-icon"></i>
              <span class="menu-title text-dark bigger-span ">Dashboard</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('userlist') }}">
             <i class="menu-icon mdi mdi-floor-plan"></i>
              <span class="menu-title text-dark bigger-span ">Manage users</span>
            </a>
          </li>
             <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="menu-icon mdi mdi-floor-plan"></i>
              <span class="menu-title">Settings</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{route('setting-index')}}">site settings</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('income-setting')}}">Income setting</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('countries')}}">country/state/cities</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('notifications.index')}}">Notifications</a></li>
               
              </ul>
            </div>
          </li>
           <li class="nav-item">
            <a class="nav-link" data-bs-toggle="collapse" href="#ui-advanced" aria-expanded="false" aria-controls="ui-advanced">
              <i class="menu-icon mdi mdi-arrow-down-drop-circle-outline"></i>
              <span class="menu-title">Support</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-advanced">
              <ul class="nav flex-column sub-menu">
                <li class="nav-item"> <a class="nav-link" href="{{url('support')}}">Open ticket</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('support-closed')}}">closed ticked</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{url('support-pending')}}">Pending ticket</a></li>
               
              </ul>
            </div>
          </li>
          <li class="nav-item">
           <a class="nav-link" href="{{route('paid-list')}}">
             <i class="menu-icon mdi mdi-account-circle-outline"></i>
              <span class="menu-title text-dark bigger-span ">Withdrawal</span>
            </a>
          </li>
               <li class="nav-item">
       <a class="nav-link" href="{{route('changepassword')}}">
                       <i class="menu-icon mdi mdi-layers-outline"></i>
              <span class="menu-title text-dark bigger-span ">Changepassword</span>
            </a>
          </li>
           <li class="nav-item">
               <a class="nav-link" href="{{route('logout')}}">
              <i class="menu-icon mdi mdi-card-text-outline"></i>
              <span class="menu-title text-dark bigger-span ">logout</span>
            </a>
          </li>
          
        </ul>
      </nav>