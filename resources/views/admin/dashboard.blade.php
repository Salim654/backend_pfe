@extends('layout')

@section('header')
    Dashboard
@endsection

@section('content')
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://netdna.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
    	body{
    margin-top:20px;
    background:#FAFAFA;
}
.order-card {
    color: #fff;
}

.bg-c-blue {
    background: linear-gradient(45deg,#4099ff,#73b4ff);
}

.bg-c-green {
    background: linear-gradient(45deg,#2ed8b6,#59e0c5);
}

.bg-c-yellow {
    background: linear-gradient(45deg,#FFB64D,#ffcb80);
}

.bg-c-pink {
    background: linear-gradient(45deg,#FF5370,#ff869a);
}


.card {
    border-radius: 5px;
    -webkit-box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    box-shadow: 0 1px 2.94px 0.06px rgba(4,26,55,0.16);
    border: none;
    margin-bottom: 30px;
    -webkit-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
}

.card .card-block {
    padding: 25px;
}

.order-card i {
    font-size: 26px;
}

.f-left {
    float: left;
}

.f-right {
    float: right;
}
    </style>
    <div class="dashboard-content">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>Use the sidebar to navigate through the admin options.</p>
        <!-- Add more dashboard-specific content here -->
    </div>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
<div class="row">
<div class="col-md-4 col-xl-3">
<div class="card bg-c-blue order-card">
<div class="card-block">
<h6 class="m-b-20">Countrys</h6>
<h2 class="text-right"><i class="fas fa-flag f-left"></i><span>{{ $countrysnb }}</span></h2>
<p class="m-b-0"><span class="f-right"></span></p>
</div>
</div>
</div>
<div class="col-md-4 col-xl-3">
    <div class="card bg-c-blue order-card">
    <div class="card-block">
    <h6 class="m-b-20">Organizations</h6>
    <h2 class="text-right"><i class="fas fa-building f-left"></i><span>{{ $ogrnb }}</span></h2>
    <p class="m-b-0"><span class="f-right"></span></p>
    </div>
    </div>
    </div>
<div class="col-md-4 col-xl-3">
    <div class="card bg-c-blue order-card">
    <div class="card-block">
    <h6 class="m-b-20">Financial managers</h6>
    <h2 class="text-right"><i class="fas fa-users f-left"></i><span> {{ $usersnb }}</span></h2>
    <p class="m-b-0"><span class="f-right"></span></p>
    </div>
    </div>
</div>
<div class="col-md-4 col-xl-3">
    <div class="card bg-c-blue order-card">
    <div class="card-block">
    <h6 class="m-b-20">Accountants</h6>
    <h2 class="text-right"><i class="fas fa-user-tie f-left"></i><span> {{ $accs }}</span></h2>
    <p class="m-b-0"><span class="f-right"></span></p>
    </div>
    </div>
</div>



</div>
</div>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
<script type="text/javascript">
	
</script>
@endsection
