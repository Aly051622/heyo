<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
</head>
<body>
    <h1>Email Verification</h1>
    <form id="verificationForm">
        <label for="verification_code">Enter Verification Code:</label>
        <input type="text" id="verification_code" name="verification_code" required>
        <button type="submit">Verify</button>
    </form>

    <!-- Link to the external JavaScript file -->
    <script src="JavaScript/verify_email.js"></script>

    <script>
// Disable right-click
document.addEventListener('contextmenu', function(event) {
    event.preventDefault();
  });
  
  // Disable F12 and other developer tools keys
  document.addEventListener('keydown', function(event) {
    if (event.keyCode == 123 || // F12
        (event.ctrlKey && event.shiftKey && event.keyCode == 73)) { // Ctrl + Shift + I (Inspect)
        event.preventDefault();
    }
  });

</script>
</body>
</html>
