<?php
    session_start();

    // Redirect to login if not logged in
    if (!isset($_SESSION['email'])) {
        header("Location: login.html");
        exit();
    }

    // Database connection settings
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "football_bets";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch teams data
    $sql = "SELECT * FROM teams";
    $result = $conn->query($sql);
    $teams = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $teams[] = $row;
        }
    }
    // Fetch bet history for the logged in user
    $email = $_SESSION['email'];
    $sql = "SELECT * FROM betstest WHERE email = '$email'";
    $result = $conn->query($sql);
    $bets = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $bets[] = $row;
        }
    }
    ?>
    <?php
    $conn->close();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <title> Football Teams</title>
        <style>
            /* สำหรับอุปกรณ์ที่มีความกว้างไม่เกิน 768px (เช่น แท็บเล็ตและโทรศัพท์) */
            @media (max-width: 768px) {
                /* ปรับขนาด font หรือ layout ให้สอดคล้องกับอุปกรณ์ที่มีขนาดเล็กขึ้น */
                body {
                    font-size: 14px;
                }
            }

            /* สำหรับอุปกรณ์ที่มีความกว้างไม่เกิน 480px (เช่น โทรศัพท์) */
            @media (max-width: 480px) {
                /* ปรับการจัดหน้าให้เหมาะสมกับขนาดหน้าจอของโทรศัพท์ */
                .container {
                    width: 100%;
                    padding: 10px;
                }
            }
            body {
                background-image: url(https://img2.pic.in.th/pic/inter-euro00.png); 
                background-size: cover; 
                background-position: center;
                font-family: Arial, sans-serif;
                background-color: #f5f5f5;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 600px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
                margin-top: 50px;
            }

            h1, h2 {
                text-align: center;
                margin-bottom: 20px;
            }

            form {
                margin-top: 30px;
            }

            form div {
                margin-bottom: 20px;
            }

            label {
                display: block;
                font-weight: bold;
                margin-bottom: 5px;
            }

            input[type="number"] {
                width: 100%;
                padding: 10px;
                border-radius: 4px;
                border: 1px solid #ccc;
            }

            img {
                display: block;
                margin: 0 auto;
                margin-bottom: 10px;
            }

            .my-button {
                background-color: #4CAF50;
                border: none;
                color: white;
                padding: 10px 20px;
                text-align: center;
                text-decoration: none;
                display: inline-block;
                font-size: 16px;
                margin: 4px 2px;
                cursor: pointer;
                border-radius: 5px;
                transition: background-color 0.3s ease;
            }

            .my-button:hover {
                background-color: #45a049;
            }

            .logout-btn {
        background-color: #f44336; /* Red */
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    /* Hover effect */
    .logout-btn:hover {
        background-color: #df362c; /* Darker Red */
    }

    /* Style for container */
    .logout-container {
        text-align: right; /* Center align content */
        margin-top: 20px; /* Add margin at the top */
    }
    .center {
        text-align: center; 
    }
    .header-img {
            width: 100px;
            height: 100px;
        }
        .modal-background {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.5); /* Black with opacity */
        }

        /* Style for modal content */
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            display: flex; 
        }
       
        /* Style for close button */
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .center {
            text-align: center;
        }
        </style>
    </head>
    <body>
    <div id="myModal" class="modal-background">
        <div class="modal-content">
        <img src="https://img5.pic.in.th/file/secure-sv1/Promotion2.jpg" alt="Promotion2" width="560px" height="600px">
        <img src="https://img2.pic.in.th/pic/Promotion3.jpg" alt="Promotion3" width="560px" height="600px">
        <span class="close">&times;</span>
        </div>
    </div>

    <script>
        // JavaScript to display modal
        window.onload = function() {
            var modal = document.getElementById("myModal");
            var closeButton = document.getElementsByClassName("close")[0];

            // When the user clicks the close button, close the modal
            closeButton.onclick = function() {
                modal.style.display = "none";
            }

            // Display the modal when the page loads
            modal.style.display = "block";
        }
    </script>
        <div class="logout-container">
                <a href="login.html" class="logout-btn">Logout</a>
                </div>
            <div class="container">
                <h1>ยินดีต้อนรับ คุณ <?php echo $_SESSION['email']; ?></h1>
                <h2>คุณมี <?php echo $_SESSION['tickets']; ?> tickets.</h2>
                <h2>ประวัติการเดิมพัน</h2>
                    <?php if (!empty($bets)): ?>
                        <center>
                            <?php foreach ($bets as $bet): ?>
                                <li>ทีม : <?php echo $bet['team_name']; ?>, จำนวนเดิมพัน: <?php echo $bet['bet_amount']; ?> tickets</li>
                            <?php endforeach; ?>
                        </center>
                            <?php else: ?>
                                <h4 class="center">ยังไม่มีประวัติการเดิมพัน</h4>
                        <?php endif; ?>
                <h1>เลือกทายทีมชาติที่จะชนะบอลยูโร</h1>
                <form action="submit_bet.php" method="post">
                    <?php foreach ($teams as $team): ?>
                        <div>
                            <img src="<?php echo $team['image_url']; ?>" alt="<?php echo $team['team_name']; ?>" width="100">
                            <label class="center"><?php echo $team['team_name']; ?></label>
                            <input type="number" name="betstest[<?php echo $team['team_name']; ?>]" min="0" max="<?php echo $_SESSION['tickets']; ?>" placeholder="Enter number of tickets">
                        </div>
                    <?php endforeach; ?>
                    <center>
                    <input class="my-button" type="submit" value="Submit">
                    </center>
                </form>
            </div>
    </body>
    </html>
