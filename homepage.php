<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointment System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        /* Banner */
        .banner {
            width: 100%;
            height: 400px; /* Increased height for more space */
            background: url("./images/homeimage.jpg");
            background-position:top;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .banner h1 {
            font-size: 48px;
            margin: 0;
        }
        .banner p {
            font-size: 22px;
            margin-top: 10px;
        }

        /* Header Section */
        .header {
            background: linear-gradient(to right, #3498db, #2ecc71);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        h1 {
            font-size: 40px;
            margin: 10px 0;
        }
        p.tagline {
            font-size: 20px;
            margin: 5px 0 25px;
        }
        .buttons a {
            display: inline-block;
            background: white;
            color: #2c3e50;
            padding: 14px 28px;
            margin: 10px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .buttons a:hover {
            background: #ecf0f1;
        }

        /* About Section */
        .about {
            background: #fff;
            padding: 40px;
            max-width: 800px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
            text-align: center;
        }
        .about h2 {
            color: #3498db;
            margin-bottom: 20px;
        }
        .about p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
        }

        /* Doctor Profile Section */
        .doctors {
            background: #fff;
            padding: 40px;
            max-width: 900px;
            margin: 40px auto;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .doctors h2 {
            text-align: center;
            color: #3498db;
            margin-bottom: 30px;
        }
        .doctor-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .doctor-card {
            background: #f9f9f9;
            width: 250px;
            margin: 15px;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .doctor-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 15px;
        }
        .doctor-card h3 {
            margin: 10px 0 5px;
            color: #2c3e50;
        }
        .doctor-card p {
            margin: 5px 0;
            font-size: 14px;
            color: #555;
        }

        /* Contact Section */
        .contact {
            background: #fff;
            padding: 30px;
            text-align: center;
            margin: 40px auto;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        .contact h2 {
            margin-bottom: 15px;
            color: #3498db;
        }
        .contact p {
            margin: 8px 0;
            font-size: 16px;
            color: #333;
        }

        /* Footer */
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- Top Banner Image -->
    <div class="banner">
        <div>
            <h1>Doctor Appointment System</h1>
        </div>
    </div>

    <!-- Header Section -->
    <div class="header">
        <p class="tagline">Avoid Hassles & Delays. Book your doctor online with ease.</p>
        <div class="buttons">
            <a href="patient/patientlogin.php">LOGIN</a>
            <a href="patient/patientregister.php">REGISTER HERE</a>
        </div>
    </div>

    <!-- About Section -->
    <div class="about">
        <h2>About Our Service</h2>
        <p>
            Our Doctor Appointment System is designed to simplify your healthcare experience.
            Whether you're feeling unwell or just need a routine checkup, you can find the right doctor,
            book appointments instantly, and avoid long queues. We connect patients with trusted medical professionals
            across Nepal, making healthcare more accessible and efficient.
        </p>
    </div>

    <!-- Doctor Profile Section -->
<div class="doctors" id="doctor-profile">
    <h2>Our Doctors</h2>
    <div class="doctor-list">
        <div class="doctor-card">
            <img src="uploads/doctor1.jpg" alt="Dr. Alinor Koirala">
            <h3>Dr. Alinor Koirala</h3>
            <p>Specialization: Dermatologist</p>
            <p>Experience: 5 years</p>
            <p>Available Days: Sunday - Friday</p>
            <p>Available Time: 1:00 PM - 5:00 PM</p>
        </div>
        <div class="doctor-card">
            <img src="uploads/doctor2.jpg" alt="Dr. Deep Karki">
            <h3>Dr. Deep Karki</h3>
            <p>Specialization: Cardiologist</p>
            <p>Experience: 10 years</p>
            <p>Available Days: Sunday - Friday</p>
            <p>Available Time: 1:00 PM - 5:00 PM</p>
        </div>
    </div>
</div>

    <!-- Contact Section -->
    <div class="contact">
        <h2>Contact Us</h2>
        <p>Email: <a href="mailto:contact@doctorappointmentsystem.com">contact@doctorappointmentsystem.com</a></p>
        <p>Phone: +977-9800000000</p>
        <p>Address: Tribhuvan University, Kathmandu, Nepal</p>
    </div>

    <!-- Footer -->
    <div class="footer">
        A Web Solution by Ronila © 2026
    </div>
</body>
</html>