<?php
require_once 'config/db_connect.php';

$sql = "SELECT package_type, base_price FROM package_prices";
$result = $conn->query($sql);
$package_prices = array();
while($row = $result->fetch_assoc()) {
    $package_prices[$row['package_type']] = $row['base_price'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $package_type = mysqli_real_escape_string($conn, $_POST['package_type']);
    $num_people = mysqli_real_escape_string($conn, $_POST['num_people']);
    $travel_date = mysqli_real_escape_string($conn, $_POST['travel_date']);
    $special_requirements = mysqli_real_escape_string($conn, $_POST['special_requirements']);
    $total_price = mysqli_real_escape_string($conn, $_POST['total_price']);

    $sql = "INSERT INTO tour_bookings (name, email, phone, package_type, num_people, travel_date, special_requirements, total_price) 
            VALUES ('$name', '$email', '$phone', '$package_type', '$num_people', '$travel_date', '$special_requirements', '$total_price')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Tour booking submitted successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Tour</title>
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
    <link rel="stylesheet" href="./assests/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;600;700;800&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <nav class="navbar" data-navbar style="background-color: #212121;">
            <div class="navbar-top">
                <a href="#" class="logo">
                    <img src="./assests/images/logo-blue.svg" alt="Tourly logo" />
                </a>
                <button class="nav-close-btn" aria-label="Close Menu" data-nav-close-btn>
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <ul class="navbar-list">
                <li><a href="index.html" class="navbar-link" data-nav-link>home</a></li>
                <li><a href="#" class="navbar-link" data-nav-link>about us</a></li>
                <li><a href="destination.html" class="navbar-link" data-nav-link>destination</a></li>
                <li><a href="package.html" class="navbar-link" data-nav-link>packages</a></li>
                <li><a href="gallery.html" class="navbar-link" data-nav-link>gallery</a></li>
                <li><a href="contact.html" class="navbar-link" data-nav-link>contact us</a></li>
                <li><a href="tour.php" class="navbar-link" data-nav-link>book tour</a></li>
                <li><a href="manage_tours.php" class="navbar-link" data-nav-link>manage tours</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="tour-form-section">
            <div class="container">
                <p class="section-subtitle">Book Your Tour</p>
                <h2 class="h2 section-title">Plan Your Perfect Trip</h2>

                <form id="tourForm" class="tour-form" method="POST" action="">
                    <div class="input-wrapper">
                        <label for="name" class="input-label">Full Name</label>
                        <input type="text" id="name" name="name" class="input-field" placeholder="Enter your name" required>
                    </div>

                    <div class="input-wrapper">
                        <label for="email" class="input-label">Email Address</label>
                        <input type="email" id="email" name="email" class="input-field" placeholder="Enter your email" required>
                    </div>

                    <div class="input-wrapper">
                        <label for="phone" class="input-label">Phone Number</label>
                        <input type="tel" id="phone" name="phone" class="input-field" placeholder="Enter your phone number" required>
                    </div>

                    <div class="input-wrapper">
                        <label for="package_type" class="input-label">Select Package Type</label>
                        <select id="package_type" name="package_type" class="input-field" required>
                            <option value="" disabled selected>Choose a package</option>
                            <?php foreach($package_prices as $type => $price): ?>
                            <option value="<?php echo $type; ?>" data-price="<?php echo $price; ?>">
                                <?php echo ucfirst($type); ?> Package - $<?php echo number_format($price, 2); ?> per person
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-wrapper">
                        <label for="num_people" class="input-label">Number of People</label>
                        <input type="number" id="num_people" name="num_people" class="input-field" min="1" max="20" required>
                    </div>

                    <div class="input-wrapper">
                        <label for="travel_date" class="input-label">Preferred Travel Date</label>
                        <input type="date" id="travel_date" name="travel_date" class="input-field" required>
                    </div>

                    <div class="input-wrapper">
                        <label for="special_requirements" class="input-label">Special Requirements</label>
                        <textarea id="special_requirements" name="special_requirements" class="input-field" rows="4" placeholder="Any special requirements or preferences?"></textarea>
                    </div>

                    <div class="price-summary">
                        <h3>Price Summary</h3>
                        <div class="price-details">
                            <p>Base Price: <span id="basePrice">$0.00</span></p>
                            <p>Number of People: <span id="peopleCount">0</span></p>
                            <p class="total-price">Total Price: <span id="totalPrice">$0.00</span></p>
                        </div>
                    </div>

                    <input type="hidden" name="total_price" id="total_price_input">
                    <button type="submit" class="btn btn-secondary">Book Now</button>
                </form>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2025 <a href="">Sidnaik04</a>. All rights reserved
                </p>
                <ul class="footer-bottom-list">
                    <li><a href="#" class="footer-bottom-link">Privacy Policy</a></li>
                    <li><a href="#" class="footer-bottom-link">Term & Condition</a></li>
                    <li><a href="#" class="footer-bottom-link">FAQ</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        $(document).ready(function() {
            function updatePrice() {
                const packageSelect = $("#package_type");
                const numPeople = parseInt($("#num_people").val()) || 0;
                const selectedOption = packageSelect.find("option:selected");
                const basePrice = parseFloat(selectedOption.data("price")) || 0;
                const totalPrice = basePrice * numPeople;

                $("#basePrice").text("$" + basePrice.toFixed(2));
                $("#peopleCount").text(numPeople);
                $("#totalPrice").text("$" + totalPrice.toFixed(2));
                $("#total_price_input").val(totalPrice.toFixed(2));
            }

            $("#package_type, #num_people").on("change", updatePrice);

            $("#tourForm").submit(function(event) {
                event.preventDefault();
                
                let name = $("#name").val().trim();
                let email = $("#email").val().trim();
                let phone = $("#phone").val().trim();
                let packageType = $("#package_type").val();
                let numPeople = $("#num_people").val();
                let travelDate = $("#travel_date").val();
                let specialRequirements = $("#special_requirements").val().trim();
                let totalPrice = $("#total_price_input").val();

                let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                let phonePattern = /^[0-9]{10,15}$/;

                if (name.length < 3) {
                    alert("Name must be at least 3 characters.");
                    return;
                }

                if (!emailPattern.test(email)) {
                    alert("Enter a valid email address.");
                    return;
                }

                if (!phonePattern.test(phone)) {
                    alert("Enter a valid phone number (10-15 digits).");
                    return;
                }

                if (!packageType) {
                    alert("Please select a package type.");
                    return;
                }

                if (numPeople < 1 || numPeople > 20) {
                    alert("Number of people must be between 1 and 20.");
                    return;
                }

                if (!travelDate) {
                    alert("Please select a travel date.");
                    return;
                }

                const submitBtn = $(this).find('button[type="submit"]');
                const originalBtnText = submitBtn.text();
                submitBtn.prop('disabled', true).text('Submitting...');

                const formData = {
                    name: name,
                    email: email,
                    phone: phone,
                    package_type: packageType,
                    num_people: numPeople,
                    travel_date: travelDate,
                    special_requirements: specialRequirements,
                    total_price: totalPrice
                };

                // Ajax Request to submit the form data
                $.ajax({
                    url: 'tour.php',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        alert('Tour booking submitted successfully!');
                        $("#tourForm")[0].reset();
                        updatePrice();
                    },
                    error: function(xhr, status, error) {
                        alert('Error submitting booking. Please try again.');
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).text(originalBtnText);
                    }
                });
            });
        });
    </script>
</body>
</html> 