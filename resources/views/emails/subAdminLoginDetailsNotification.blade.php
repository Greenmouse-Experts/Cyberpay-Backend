<!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>{{config('app.name')}}</title>
          <style>
              body {
                  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                  background-color: #e0e0e0;
                  display: flex;
                  justify-content: center;
                  align-items: center;
                  height: 100vh;
                  margin: 0;
                  padding: 0;
              }

              .notification-container {
                  display: flex;
                  flex-direction: column;
                  align-items: flex-start;
                  justify-content: center;
                  width: 100%;
                  max-width: 500px;
                  background: #ffffff;
                  border-radius: 10px;
                  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                  padding: 20px;
                  margin: 20px;
                  animation: fadeIn 1s ease-out;
              }

              .notification-logo {
                  max-width: 120px;
                  margin-bottom: 20px;
                  animation: slideIn 0.5s ease-out;
              }

              .notification-container h1 {
                  color: #f40c3b;
                  font-size: 28px;
                  margin-bottom: 15px;
                  animation: fadeInUp 0.5s ease-out;
              }

              .notification-container p {
                  color: #666;
                  font-size: 16px;
                  line-height: 1.6;
                  margin-bottom: 15px;
                  animation: fadeInUp 0.7s ease-out;
              }

              /* Animations */
              @keyframes fadeIn {
                  from {
                      opacity: 0;
                  }
                  to {
                      opacity: 1;
                  }
              }

              @keyframes fadeInUp {
                  from {
                      opacity: 0;
                      transform: translateY(20px);
                  }
                  to {
                      opacity: 1;
                      transform: translateY(0);
                  }
              }

              @keyframes slideIn {
                  from {
                      transform: translateX(-50px);
                      opacity: 0;
                  }
                  to {
                      transform: translateX(0);
                      opacity: 1;
                  }
              }

              /* Media query for responsive design */
              @media (max-width: 600px) {
                  .notification-container {
                      padding: 15px;
                      margin: 10px;
                  }

                  .notification-container h1 {
                      font-size: 24px;
                  }

                  .notification-container p {
                      font-size: 14px;
                  }
              }
          </style>
      </head>
      <body>
          <div class="notification-container">
              <img src="{{ URL::asset('cyberpaylogo.png') }}" alt="{{config('app.name')}} Logo" class="notification-logo">
              <h1>Your {{config('app.name')}} New Login Details</h1>
              <p>Welcome <strong>{{$name}}</strong>,</p>
              <p>Your account has been created.</p>
              <p>Here are your login details:</p>
              <span>Email: <b>{{$email}}</b></span><br/>
              <span>Password: <b>{{$password}}</b></span>
              <span>Role: <b>{{$role}}</b></span>
              <p>Please change your password after logging in.</p>
              <p>Best regards,<br>{{config('app.name')}}</p>
          </div>
      </body>
    </html>
