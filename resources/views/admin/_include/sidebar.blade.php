<nav class="sb-sidenav accordion sb-sidenav-white border-end" id="sidenavAccordion">
    <div class="sb-sidenav-menu bg-white">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
            @hasanyrole('superadmin|user')
            <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard @hasanyrole('superadmin|admin') User @endhasanyrole
            </a>
            @endhasanyrole

            @hasanyrole('superadmin|admin')
            <a class="nav-link {{ Route::is('dashboard.admin') ? 'active' : '' }}" href="{{ route('dashboard.admin') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard Admin
            </a>
            @endhasanyrole

            
            <a class="nav-link {{ Route::is('dashboard.personil') ? 'active' : '' }}" href="{{ route('dashboard.personil') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Data Personil
            </a>
            

            @hasanyrole('superadmin|admin')
            <div class="sb-sidenav-menu-heading">Config</div>
            @endhasanyrole

            @hasanyrole('superadmin|admin')
            <a class="nav-link {{ Route::is('user.group') ? 'active' : '' }} {{ Request::is('group-setting/*') ? 'active' : '' }}" href="{{ route('user.group') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-people-group"></i></div>
                Group
            </a>
            <a class="nav-link {{ Route::is('user.position') ? 'active' : '' }} {{ Request::is('position-setting/*') ? 'active' : '' }}" href="{{ route('user.position') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user-tie"></i></div>
                Jabatan
            </a>
            @endhasanyrole

            @role('superadmin')
            <div class="collapse " id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav ">
                    <a class="nav-link "
                        href="#">Zona</a>
                    <a class="nav-link " href="#">Gedung</a>
                    <a class="nav-link " href="#">Lantai</a>
                    <a class="nav-link " href="#">Tipe APAR</a>
                    <a class="nav-link " href="#">Tipe Hydrant</a>
                    <a class="nav-link " href="#">Kondisi Peralatan</a>
                    <a class="nav-link " href="#">Group</a>
                    <a class="nav-link " href="#">Merek</a>
                    <a class="nav-link " href="#">Status</a>
                    <a class="nav-link " href="#">Status Pegawai</a>
                    <a class="nav-link " href="#">Jabatan</a>
                    <a class="nav-link " href="#">Kompetensi</a>
                </nav>
            </div>
            <a class="nav-link 
            {{ Route::is('master.index') ? 'active' : '' }}
            {{ Route::is('zones.index') ? 'active' : '' }} {{ Request::is('zones/*') ? 'active' : '' }}
            {{ Route::is('buildings.index') ? 'active' : '' }} {{ Request::is('buildings/*') ? 'active' : '' }}
            {{ Route::is('apar-types.index') ? 'active' : '' }} {{ Request::is('apar-types/*') ? 'active' : '' }}
            {{ Route::is('floors.index') ? 'active' : '' }} {{ Request::is('floors/*') ? 'active' : '' }}
            {{ Route::is('hydrant-types.index') ? 'active' : '' }} {{ Request::is('hydrant-types/*') ? 'active' : '' }}
            {{ Route::is('extinguisher-conditions.index') ? 'active' : '' }} {{ Request::is('extinguisher-conditions/*') ? 'active' : '' }}
            {{ Route::is('groups.index') ? 'active' : '' }} {{ Request::is('groups/*') ? 'active' : '' }}
            {{ Route::is('brands.index') ? 'active' : '' }} {{ Request::is('brands/*') ? 'active' : '' }}
            {{ Route::is('statuses.index') ? 'active' : '' }} {{ Request::is('statuses/*') ? 'active' : '' }}
            {{ Route::is('employee-types.index') ? 'active' : '' }} {{ Request::is('employee-types/*') ? 'active' : '' }}
            {{ Route::is('positions.index') ? 'active' : '' }} {{ Request::is('positions/*') ? 'active' : '' }}
            {{ Route::is('competencies.index') ? 'active' : '' }} {{ Request::is('competencies/*') ? 'active' : '' }}
            {{ Route::is('apar-pressures.index') ? 'active' : '' }} {{ Request::is('apar-pressures/*') ? 'active' : '' }}
            {{ Route::is('apar-cylinders.index') ? 'active' : '' }} {{ Request::is('apar-cylinders/*') ? 'active' : '' }}
            {{ Route::is('apar-pin-seals.index') ? 'active' : '' }} {{ Request::is('apar-pin-seals/*') ? 'active' : '' }}
            {{ Route::is('apar-hoses.index') ? 'active' : '' }} {{ Request::is('apar-hoses/*') ? 'active' : '' }}
            {{ Route::is('apar-handles.index') ? 'active' : '' }} {{ Request::is('apar-handles/*') ? 'active' : '' }}
            {{ Route::is('hydrant-doors.index') ? 'active' : '' }} {{ Request::is('hydrant-doors/*') ? 'active' : '' }}
            {{ Route::is('hydrant-couplings.index') ? 'active' : '' }} {{ Request::is('hydrant-couplings/*') ? 'active' : '' }}
            {{ Route::is('hydrant-main-valve.index') ? 'active' : '' }} {{ Request::is('hydrant-main-valve/*') ? 'active' : '' }}
            {{ Route::is('hydrant-hoses.index') ? 'active' : '' }} {{ Request::is('hydrant-hoses/*') ? 'active' : '' }}
            {{ Route::is('hydrant-nozzles.index') ? 'active' : '' }} {{ Request::is('hydrant-nozzles/*') ? 'active' : '' }}
            {{ Route::is('hydrant-safety-markings.index') ? 'active' : '' }} {{ Request::is('hydrant-safety-markings/*') ? 'active' : '' }}
            {{ Route::is('hydrant-guides.index') ? 'active' : '' }} {{ Request::is('hydrant-guides/*') ? 'active' : '' }}
             
            " href="{{ route('master.index') }}">
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Master
            </a>
            @endrole

            @hasanyrole('superadmin|admin')
                
            <a class="nav-link {{ Route::is('zone-assignments.index') ? 'active' : '' }} {{ Request::is('zone-assignments/*') ? 'active' : '' }}" href="{{ route('zone-assignments.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-calendar-days"></i></div>
                Jadwal Regu
            </a>


            <a class="nav-link collapsed 
            {{ Route::is('summary-apar.index') ? 'active' : '' }} {{ Request::is('summary-apar/*') ? 'active' : '' }}
            {{ Route::is('summary-hydrant.index') ? 'active' : '' }} {{ Request::is('summary-hydrant/*') ? 'active' : '' }}
          " href="#" data-bs-toggle="collapse"
                data-bs-target="#collapseSummary" aria-expanded="false" aria-controls="collapseSummary">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-table-list"></i></div>
                Summary
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse " id="collapseSummary" aria-labelledby="headingOne"
                data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav ">
                    <a class="nav-link {{ Route::is('summary-apar.index') ? 'active' : '' }} {{ Request::is('summary-apar/*') ? 'active' : '' }} " href="{{ route('summary-apar.index') }}">APAR</a>
                    <a class="nav-link {{ Route::is('summary-hydrant.index') ? 'active' : '' }} {{ Request::is('summary-hydrant/*') ? 'active' : '' }} " href="{{ route('summary-hydrant.index') }}">Hydrant</a>
                </nav>
            </div>
            @endhasanyrole
            <div class="sb-sidenav-menu-heading">Checklist</div>
            {{-- <a class="nav-link {{ Route::is('apar-check.progress') ? 'active' : '' }} {{ Request::is('apar/*') ? 'active' : '' }}" href="{{ route('apar-check.progress') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-bars-progress"></i></div>
                Progress
            </a> --}}
            <a class="nav-link {{ Route::is('apar-check.to-check') ? 'active' : '' }} {{ Request::is('apar-check/*') ? 'active' : '' }}" href="{{ route('apar-check.to-check') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fire-extinguisher"></i></div>
                APAR
            </a>
            <a class="nav-link {{ Route::is('hydrant-check.to-check') ? 'active' : '' }} {{ Request::is('hydrant-check/*') ? 'active' : '' }}" href="{{ route('hydrant-check.to-check') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                Hydrant
            </a>

            <div class="sb-sidenav-menu-heading">Summary @hasanyrole('superadmin|admin') (User) @endhasanyrole</div>
            <a class="nav-link {{ Route::is('apar-check.index') ? 'active' : '' }} {{ Request::is('apar/*') ? 'active' : '' }}" href="{{ route('apar-check.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fire-extinguisher"></i></div>
                APAR
            </a>
            <a class="nav-link {{ Route::is('hydrant-check.index') ? 'active' : '' }} {{ Request::is('apar/*') ? 'active' : '' }}" href="{{ route('hydrant-check.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                Hydrant
            </a>


            @hasanyrole('superadmin|admin')
            <div class="sb-sidenav-menu-heading">Mapping</div>
            <a class="nav-link {{ Route::is('apar.index') ? 'active' : '' }} {{ Request::is('apar/*') ? 'active' : '' }}" href="{{ route('apar.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-fire-extinguisher"></i></div>
                APAR
            </a>
            <a class="nav-link {{ Route::is('hydrant.index') ? 'active' : '' }} {{ Request::is('hydrant/*') ? 'active' : '' }}" href="{{ route('hydrant.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-box"></i></div>
                Hydrant
            </a>
            @endhasanyrole

            <div class="sb-sidenav-menu-heading">Users</div>
            <a class="nav-link {{ Route::is('user.index') ? 'active' : '' }} {{ Request::is('user/*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-user"></i></div>
                Karyawan
            </a>

            @hasanyrole('superadmin')
            <a class="nav-link collapsed 
            {{ Route::is('permission.index') ? 'active' : '' }} {{ Request::is('permission/*') ? 'active' : '' }}
            {{ Route::is('role.index') ? 'active' : '' }} {{ Request::is('role/*') ? 'active' : '' }}
            " href="#" data-bs-toggle="collapse"
                  data-bs-target="#collapseRolePermissions" aria-expanded="false"
                  aria-controls="collapseRolePermissions">
                  <div class="sb-nav-link-icon"><i class="fa-solid fa-user-shield"></i></div>
                  Role & Permis..
                  <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
              </a>
              <div class="collapse " id="collapseRolePermissions" aria-labelledby="headingOne"
                  data-bs-parent="#sidenavAccordion">
                  <nav class="sb-sidenav-menu-nested nav ">
                      <a class="nav-link {{ Route::is('role.index') ? 'active' : '' }} {{ Request::is('role/*') ? 'active' : '' }}" href="{{ route('role.index') }}">Role</a>
                      <a class="nav-link {{ Route::is('permission.index') ? 'active' : '' }} {{ Request::is('permission/*') ? 'active' : '' }}" href="{{ route('permission.index') }}">Permissions</a>
  
                  </nav>
              </div>
            @endhasanyrole


        </div>
    </div>
    {{-- <div class="sb-sidenav-footer bg-white border-top">
      <div class="small">Logged in as:</div>
      {{ Auth::user()->name }}
  </div> --}}
    <div class="sb-sidenav-footer bg-white border-top">
        <div class="small">Logged in as:</div>
        {{-- Ambil kata pertama --}}
        {{ explode(' ', Auth::user()->name)[0] }}

        {{-- Atau ambil inisial --}}
        {{-- {{ collect(explode(' ', Auth::user()->name))->map(fn($word) => $word[0])->implode('') }} --}}
    </div>
</nav>
