<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ url('/') }}">{{ config('app.name') }}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}">KP</a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li><a class="nav-link" href="{{ url('/') }}"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
        <li class="menu-header"><i>Menu</i></li>
        {{-- <li><a class="nav-link" href="{{ url('/permissions') }}"><i class="fas fa-key"></i> <span>Permissions</span></a></li>
        <li><a class="nav-link" href="{{ url('/roles') }}"><i class="fas fa-user-tag"></i> <span>Roles</span></a></li> --}}

        
        @if(session('role') !== 'barista')
            <li><a class="nav-link" href="{{ url('/users') }}"><i class="fas fa-users-cog"></i> <span>Data Pengguna</span></a></li>
            <!-- <li><a class="nav-link" href="{{ url('/users') }}"><i class="fas fa-users-cog"></i> <span>Data Pengguna</span></a></li> -->
            <!-- <li><a class="nav-link" href="{{ url('/rpph') }}"><i class="fas fa-file"></i> <span>Data RPPH</span></a></li> -->
        @endif
        <li><a class="nav-link" href="{{ url('/barangs') }}"><i class="fas fa-box"></i> <span>Data Barang</span></a></li>
        <li><a class="nav-link" href="{{ url('/produks') }}"><i class="fas fa-bars"></i> <span>Data Produk</span></a></li>
        <li><a class="nav-link" href="{{ url('/customers') }}"><i class="fas fa-users-cog"></i> <span>Data Pelanggan</span></a></li>
        <li><a class="nav-link" href="{{ url('/penjualans') }}"><i class="fas fa-cart-plus"></i> <span>Penjualan</span></a></li>
        <li><a class="nav-link" href="{{ url('/pembelians') }}"><i class="fas fa-store"></i> <span>Pembelian</span></a></li>
        <li><a class="nav-link" href="{{ url('/riwayatpenjualans') }}"><i class="fas fa-history"></i> <span>Riwayat Penjualan</span></a></li>
        
    </ul>
</aside>
