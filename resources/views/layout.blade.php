<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        
        /* Sidebar Styles */
        .sidebar {
            border-top-right-radius: 5%;
            border-bottom-right-radius: 5%;
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #3572EF;
            overflow-x: hidden;
            padding-top: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .sidebar .logo img {
            width: 100px; /* Adjust the width as needed */
            border-radius: 50%; /* Make it circular */
            background-color: white; /* White background */
            padding: 5px; /* Optional: Add padding */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Optional: Add shadow */
        }

        .sidebar .menu-items {
            margin-top: -270px;
            padding-left: 10px; 
            padding-right: 10px;
        }

        .sidebar a {
            padding: 10px 20px;
            text-decoration: none;
            font-size: 16px; 
            color: white;
            display: flex;
            align-items: center;
            transition: 0.3s;
        }

        .sidebar a i {
            margin-right: 10px;
        }

        .sidebar a:hover {
            background-color: #3ABEF9;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .header {
            font-family: Poppins-Bold;
            font-size: 24px;
            color: #3572EF;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('images/icons/icon.png') }}" alt="Logo">
        </div>
        <div class="menu-items">
            <a href="{{ url('/admin/dashboard') }}"><i class="fas fa-tachometer-alt"></i>Dashboard</a>
            <a href="{{ url('/admin/countrys') }}"><i class="fas fa-flag"></i>Countrys</a>
            <a href="{{ url('/admin/organizations') }}"><i class="fas fa-building"></i>Organizations</a>
            <a href="{{ url('/admin/users') }}"><i class="fas fa-users"></i>Financial managers</a>
            <a href="{{ url('/admin/accountants') }}"><i class="fas fa-user-tie"></i>Accountants</a>
        </div>
        <div class="logout">
            <form id="logout-form" action="{{ url('/logout') }}" method="POST">
                @csrf <!-- CSRF Protection -->
                <button type="submit" style="background: none; border: none; color: white; padding: 30px; display: flex; align-items: center;">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </button>
            </form>
        </div>
    </div>
    
    <div class="main-content">
        <div class="header">
            @yield('header')
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
   
</body>
</html>
