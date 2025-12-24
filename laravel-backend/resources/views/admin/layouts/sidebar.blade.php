<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  
    <a href="" class="brand-link">
    <span class="brand-text font-weight-light">Admin Panel</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
        <li class="nav-item">
    <a href="{{ route('category.index') }}" class="nav-link">
        <i class="nav-icon fas fa-list"></i>
        <p>Categories</p>
    </a>
</li>
 <li class="nav-item">
    <a href="{{ route('product.index') }}" class="nav-link">
         <i class="nav-icon fas fa-box"></i>
        <p>Products</p>
    </a>
</li>
        <li class="nav-item">
          <a href="{{ route('admin.orders.index') }}" class="nav-link">
            <i class="nav-icon fas fa-receipt"></i>
            <p>Orders</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>
