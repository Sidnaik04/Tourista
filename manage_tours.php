<?php
require_once 'config/db_connect.php';

// Delete operation
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($conn, $_GET['delete']);
    $sql = "DELETE FROM tour_bookings WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Tour booking deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting record: " . $conn->error . "');</script>";
    }
}


// Update operation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $package_type = mysqli_real_escape_string($conn, $_POST['package_type']);
    $num_people = mysqli_real_escape_string($conn, $_POST['num_people']);
    $travel_date = mysqli_real_escape_string($conn, $_POST['travel_date']);
    $special_requirements = mysqli_real_escape_string($conn, $_POST['special_requirements']);

    $sql = "UPDATE tour_bookings SET 
            name = '$name',
            email = '$email',
            phone = '$phone',
            package_type = '$package_type',
            num_people = '$num_people',
            travel_date = '$travel_date',
            special_requirements = '$special_requirements'
            WHERE id = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Tour booking updated successfully!');</script>";
    } else {
        echo "<script>alert('Error updating record: " . $conn->error . "');</script>";
    }
}

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$where_clause = $search ? "WHERE name LIKE '%$search%' OR package_type LIKE '%$search%'" : "";

// Fetch all bookings
$sql = "SELECT * FROM tour_bookings $where_clause ORDER BY travel_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tours</title>
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
        <section class="manage-tours-section">
            <div class="container">
                <p class="section-subtitle">Manage Tours</p>
                <h2 class="h2 section-title">Tour Bookings</h2>

                <div class="search-box">
                    <form method="GET" action="">
                        <input type="text" name="search" placeholder="Search by name or package type" value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit">Search</button>
                    </form>
                </div>

                <?php if ($result->num_rows > 0): ?>
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Package Type</th>
                                <th>Number of People</th>
                                <th>Travel Date</th>
                                <th>Total Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars($row['package_type']); ?></td>
                                    <td><?php echo htmlspecialchars($row['num_people']); ?></td>
                                    <td><?php echo htmlspecialchars($row['travel_date']); ?></td>
                                    <td>$<?php echo number_format($row['total_price'], 2); ?></td>
                                    <td class="action-buttons">
                                        <button class="edit-btn" onclick='openEditModal(<?php echo json_encode($row); ?>)'>Edit</button>
                                        <button class="delete-btn" onclick="deleteBooking(<?php echo $row['id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-bookings">
                        <p>No bookings found</p>
                        <a href="tour.php">Create New Booking</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Booking</h2>
            <form id="editForm" method="POST" action="">
                <input type="hidden" name="id" id="edit_id">
                <input type="hidden" name="update" value="1">
                
                <div class="input-wrapper">
                    <label for="edit_name">Name</label>
                    <input type="text" id="edit_name" name="name" required>
                </div>

                <div class="input-wrapper">
                    <label for="edit_email">Email</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>

                <div class="input-wrapper">
                    <label for="edit_phone">Phone</label>
                    <input type="tel" id="edit_phone" name="phone" required>
                </div>

                <div class="input-wrapper">
                    <label for="edit_package_type">Package Type</label>
                    <select id="edit_package_type" name="package_type" required>
                        <option value="luxury">Luxury Package</option>
                        <option value="standard">Standard Package</option>
                        <option value="budget">Budget Package</option>
                        <option value="family">Family Package</option>
                        <option value="honeymoon">Honeymoon Package</option>
                    </select>
                </div>

                <div class="input-wrapper">
                    <label for="edit_num_people">Number of People</label>
                    <input type="number" id="edit_num_people" name="num_people" min="1" max="20" required>
                </div>

                <div class="input-wrapper">
                    <label for="edit_travel_date">Travel Date</label>
                    <input type="date" id="edit_travel_date" name="travel_date" required>
                </div>

                <div class="input-wrapper">
                    <label for="edit_special_requirements">Special Requirements</label>
                    <textarea id="edit_special_requirements" name="special_requirements" rows="4"></textarea>
                </div>

                <button type="submit" class="btn btn-secondary">Update Booking</button>
            </form>
        </div>
    </div>

    <script>
        // Search functionality
        let searchTimeout;
        $('input[name="search"]').on('input', function() {
            clearTimeout(searchTimeout);
            const searchValue = $(this).val();
            
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: 'manage_tours.php',
                    type: 'GET',
                    data: { search: searchValue },
                    success: function(response) {
                        const tableContent = $(response).find('.bookings-table').html();
                        if (tableContent) {
                            $('.bookings-table').html(tableContent);
                        } else {
                            $('.bookings-table').parent().html($(response).find('.no-bookings').html());
                        }
                    }
                });
            }, 300);
        });

        function openEditModal(booking) {
            document.getElementById('edit_id').value = booking.id;
            document.getElementById('edit_name').value = booking.name;
            document.getElementById('edit_email').value = booking.email;
            document.getElementById('edit_phone').value = booking.phone;
            document.getElementById('edit_package_type').value = booking.package_type;
            document.getElementById('edit_num_people').value = booking.num_people;
            document.getElementById('edit_travel_date').value = booking.travel_date;
            document.getElementById('edit_special_requirements').value = booking.special_requirements;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // AJAX form submission for editing
        $("#editForm").submit(function(event) {
            event.preventDefault();
            
            const submitBtn = $(this).find('button[type="submit"]');
            const originalBtnText = submitBtn.text();
            submitBtn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: 'manage_tours.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert('Tour booking updated successfully!');
                    closeEditModal();
                    location.reload();
                },
                error: function() {
                    alert('Error updating booking. Please try again.');
                },
                complete: function() {
                    submitBtn.prop('disabled', false).text(originalBtnText);
                }
            });
        });

        function deleteBooking(id) {
            if (confirm('Are you sure you want to delete this booking?')) {
                $.ajax({
                    url: 'manage_tours.php',
                    type: 'GET',
                    data: { delete: id },
                    success: function(response) {
                        alert('Tour booking deleted successfully!');
                        $(`tr:has(button[onclick="deleteBooking(${id})"])`).fadeOut(300, function() {
                            $(this).remove();
                            if ($('.bookings-table tbody tr').length === 0) {
                                $('.bookings-table').parent().html(`
                                    <div class="no-bookings">
                                        <p>No bookings found</p>
                                        <a href="tour.php">Create New Booking</a>
                                    </div>
                                `);
                            }
                        });
                    },
                    error: function() {
                        alert('Error deleting booking. Please try again.');
                    }
                });
            }
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                closeEditModal();
            }
        }
    </script>
</body>
</html> 