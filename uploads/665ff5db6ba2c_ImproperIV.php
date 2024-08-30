<?php
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate name
    if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
        $errors[] = 'Name is required.';
    }

    // Validate email
    if (!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email address.';
    }

    // If there are no errors, process the form
    if (empty($errors)) {
        // Process the form data (e.g., save to database, send email, etc.)
        // ...
        echo 'Form submitted successfully!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Validation Example</title>
</head>
<body>
    <h1>Submit Your Information</h1>
    <?php if (!empty($errors)): ?>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <button type="submit">Submit</button>
    </form>
</body>
</html>
