<header>
    <style>
        :root {
    --primary-color: #008752; /* INEC Green */
    --secondary-color: #fff;
    --accent-color: #2D71B6; /* Blue */
    --dark-color: #333;
    --light-color: #f8f8f8;
    --border-color: #ddd;
}

        header {
    background-color: var(--primary-color);
    color: var(--secondary-color);
    padding: 15px 0;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo-container {
    display: flex;
    align-items: center;
}

.logo {
    width: 60px;
    height: 60px;
    background-color: var(--secondary-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    color: var(--primary-color);
    margin-right: 15px;
}

.header-text h1 {
    font-size: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

nav ul {
    display: flex;
    list-style: none;
}

nav ul li {
    margin-left: 20px;
}

nav ul li a {
    color: var(--secondary-color);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

nav ul li a:hover {
    opacity: 0.8;
}
    </style>
<div class='container'>
    <div class='header-content'>
        <div class='logo-container'>
            <div class='logo'>INEC</div>
            <div class='header-text'>
                <h1>INEC Delta Results</h1>
            </div>
        </div>
        <nav>
            <ul>
                <li><a href='index.html' class='back-btn'>Back to Home</a></li>
            </ul>
        </nav>
    </div>
</div>
</header>