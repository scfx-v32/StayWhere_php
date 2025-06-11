<?php
// Start output buffering at the very top
ob_start();
session_start();

// Check if user is logged in
if (empty($_SESSION['user']) || !isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    ob_end_flush();
    exit();
}

require_once 'config.php';


// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user']['id']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);



// Handle profile picture upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    $target_dir = "uploads/profile_pics/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check !== false) {
        // Generate unique filename
        $new_filename = "user_" . $_SESSION['user']['id'] . "." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database
            $stmt = $pdo->prepare("UPDATE users SET profile_picture = ? WHERE id = ?");
            if ($stmt->execute([$target_file, $_SESSION['user']['id']])) {
                $_SESSION['user']['profile_picture'] = $target_file;
                $_SESSION['success'] = "Profile picture updated successfully";
            } else {
                $_SESSION['error'] = "Failed to update profile picture";
            }
            header("Location: profile.php");
            ob_end_flush();
            exit();
        }
    }
}

// Handle profile info update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_info'])) {
    // Get current data from database first
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user']['id']]);
    $current_data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Sanitize and prepare update data
    $update_data = [
        'name' => isset($_POST['name']) ? trim(htmlspecialchars($_POST['name'])) : $current_data['name'],
        'email' => isset($_POST['email']) ? trim(htmlspecialchars($_POST['email'])) : $current_data['email'],
        'telephone' => isset($_POST['telephone']) ? trim(htmlspecialchars($_POST['telephone'])) : $current_data['telephone'],
        'about_me' => isset($_POST['about_me']) ? trim($_POST['about_me']) : $current_data['about_me'],
        'id' => $_SESSION['user']['id']
    ];

    // Validate email
    if (!filter_var($update_data['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
    } else {
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, telephone = :telephone, about_me = :about_me WHERE id = :id");
        if ($stmt->execute($update_data)) {
            // Update session data
            $_SESSION['user']['name'] = $update_data['name'];
            $_SESSION['user']['email'] = $update_data['email'];
            $_SESSION['user']['telephone'] = $update_data['telephone'];
            $_SESSION['user']['about_me'] = $update_data['about_me'];
            $_SESSION['success'] = "Profile updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update profile";
        }
    }
    header("Location: profile.php");
    ob_end_flush();
    exit();
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['error'] = "All password fields are required";
    } elseif (!password_verify($current_password, $user['password'])) {
        $_SESSION['error'] = "Current password is incorrect";
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['error'] = "New passwords don't match";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        if ($stmt->execute([$hashed_password, $_SESSION['user']['id']])) {
            $_SESSION['success'] = "Password updated successfully";
        } else {
            $_SESSION['error'] = "Failed to update password";
        }
    }
    header("Location: profile.php");
    ob_end_flush();
    exit();
}

// Handle account deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_account'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    if ($stmt->execute([$_SESSION['user']['id']])) {
        session_destroy();
        header("Location: index.php");
        ob_end_flush();
        exit();
    } else {
        $_SESSION['error'] = "Failed to delete account";
        header("Location: profile.php");
        ob_end_flush();
        exit();
    }
}

// End output buffering and flush if we reach this point
ob_end_flush();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>StayWhere - My Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <?php include_once 'headers/header.php'; ?>

    <!-- Profile Section -->
    <div class="container mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Info Card (Left) -->
            <div class="bg-white p-6 rounded-xl shadow-lg md:col-span-1">
                <div class="text-center">
                    <img src="<?php echo $user['profile_picture'] ? $user['profile_picture'] : 'uploads\profile_pics\no_pfp.jpg'; ?>"
                        alt="Profile Photo" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
                    <h2 class="text-2xl font-bold"><?php echo htmlspecialchars($user['name']); ?></h2>
                    <p class="text-gray-600"><?php echo ucfirst($user['role']); ?> Account</p>
                </div>

                <div class="mt-6 text-center">
                    <button onclick="document.getElementById('profile-picture-modal').classList.remove('hidden')"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-camera mr-2"></i>Edit Photo
                    </button>
                </div>

                <div class="mt-4 text-center">
                    <form method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This cannot be undone.');">
                        <button type="submit" name="delete_account"
                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-trash-alt mr-2"></i>Delete Account
                        </button>
                    </form>
                </div>
            </div>

            <!-- Profile Details (Right) -->
            <div class="md:col-span-2">
                <h1 class="text-2xl font-bold mb-6">Welcome, <?php echo htmlspecialchars($user['name']); ?></h1>

                <form method="POST" class="bg-white p-6 rounded-xl shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Name</label>
                            <div class="flex">
                                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>"
                                    class="w-full p-2 border rounded-l disabled:bg-gray-100" disabled id="name-input">
                                <button type="button" class="bg-gray-200 px-3 rounded-r edit-btn" data-target="name-input">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 mb-2">Email</label>
                            <div class="flex">
                                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>"
                                    class="w-full p-2 border rounded-l disabled:bg-gray-100" disabled id="email-input">
                                <button type="button" class="bg-gray-200 px-3 rounded-r edit-btn" data-target="email-input">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Telephone</label>
                        <div class="flex">
                            <input type="tel" name="telephone" value="<?php echo htmlspecialchars($user['telephone']); ?>"
                                class="w-full p-2 border rounded-l disabled:bg-gray-100" disabled id="telephone-input">
                            <button type="button" class="bg-gray-200 px-3 rounded-r edit-btn" data-target="telephone-input">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        </div>
                    </div>

                    <?php if ($user['role'] !== 'admin'): ?>
                        <div class="mb-6">
                            <label class="block text-gray-700 mb-2">About Me</label>
                            <div class="flex">
                                <textarea name="about_me" class="w-full p-2 border rounded-l disabled:bg-gray-100"
                                    disabled id="about-me-input" rows="3"><?php echo htmlspecialchars($user['about_me']); ?></textarea>
                                <button type="button" class="bg-gray-200 px-3 rounded-r edit-btn self-start mt-2" data-target="about-me-input">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>

                    <button type="submit" name="update_info" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg" onclick="enableAllInputs()">
                        <i class="fas fa-save mr-2"></i>Save Changes
                    </button>
                </form>

                <!-- Password Change Form -->
                <form method="POST" class="bg-white p-6 rounded-xl shadow-lg mt-6">
                    <h3 class="text-xl font-semibold mb-4">Change Password</h3>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Current Password</label>
                        <input type="password" name="current_password" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">New Password</label>
                        <input type="password" name="new_password" class="w-full p-2 border rounded" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="w-full p-2 border rounded" required>
                    </div>

                    <button type="submit" name="change_password" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-key mr-2"></i>Change Password
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div id="profile-picture-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white p-6 rounded-xl w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Update Profile Picture</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_picture" accept="image/*" class="mb-4" required>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('profile-picture-modal').classList.add('hidden')"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Include Footer -->
    <?php include 'headers/footer.php'; ?>

    <script>
        $(document).ready(function() {
            // Enable editing when pencil icon is clicked
            $('.edit-btn').click(function() {
                const target = $(this).data('target');
                const input = $('#' + target);

                if (input.prop('disabled')) {
                    input.prop('disabled', false);
                    $(this).html('<i class="fas fa-check"></i>');
                    input.focus();
                } else {
                    input.prop('disabled', true);
                    $(this).html('<i class="fas fa-pencil-alt"></i>');
                }
            });
        });

        // Enable all disabled inputs before submitting the form
        function enableAllInputs() {
            $('form input:disabled, form textarea:disabled').prop('disabled', false);
        }
    </script>
</body>

</html>