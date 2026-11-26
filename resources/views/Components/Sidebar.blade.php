<style>
  /* Border transparan dari awal supaya menu tidak bergeser saat hover */
  .sidebar .nav li > a {
    border: 1px solid transparent !important;
    transition: border-color 0.2s ease, background-color 0.2s ease;
  }

  /* Hover pada menu yang tidak aktif: muncul border */
  .sidebar .nav li:not(.active) > a:hover {
    border-color: #51cbce !important;
  }

  /* Menu aktif: tampil sebagai kotak berwarna */
  .sidebar[data-active-color] .nav li.active > a {
    background-color: #51cbce !important;
    border-color: #51cbce !important;
    border-radius: 0.25rem !important;
  }
  .sidebar[data-active-color] .nav li.active > a i,
  .sidebar[data-active-color] .nav li.active > a p {
    color: #ffffff !important;
    opacity: 1 !important;
  }
</style>

<div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo">

      <a href="" class="simple-text logo-normal">
        <br>
        <!-- <div class="logo-image-big">
          <img src="../assets/img/logo-big.png">
        </div> -->
      </a>
    </div>
    <div class="sidebar-wrapper">
      <ul class="nav">
  <li class="{{ request()->is('report*') ? 'active' : '' }}">
    <a href="/report">
      <i class="nc-icon nc-chart-bar-32"></i>
      <p>Laporan</p>
    </a>
  </li>
  <li class="{{ request()->is('dashboard*', 'table*', '*/edit/*') ? 'active' : '' }}">
    <a href="/dashboard">
      <i class="nc-icon nc-bank"></i>
      <p>Dashboard</p>
    </a>
  </li>
  <li class="{{ request()->is('add*') ? 'active' : '' }}">
    <a href="/add">
      <i class="nc-icon nc-simple-add"></i>
      <p>Tambah Data</p>
    </a>
  </li>
</ul>
      </ul>
    </div>
  </div>
