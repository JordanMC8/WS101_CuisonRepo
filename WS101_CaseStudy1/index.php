<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professional Resume</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial;
        }
        body {
            background-color: #1a365d;
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .resume-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        .parentDiv1 {
            display: flex;
            align-items: center;
            gap: 40px;
            background-color: #2563eb;
            padding: 40px;
            color: white;
        }
        .resumePhoto {
            width: 160px;
            height: 160px;
            flex-shrink: 0;
        }
        .resumePhoto img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border: 4px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .personalInfo {
            flex-grow: 1;
        }
        .name {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .career {
            font-size: 22px;
            font-weight: 300;
            margin-bottom: 30px;
            opacity: 0.9;
        }
        .contact-table {
            width: 100%;
            border: 1px solid transparent;
            border-collapse: collapse;
            color: white;
        }
        .contact-table td {
            padding: 12px 12px;
            vertical-align: middle;
            font-size: 12px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            
        }
        .contact-table tr td:first-child {
            width: 380px;
        }
        .content-section {
            padding: 30px 40px;
        }
        .section-title {
            font-size: 24px;
            color: #2563eb;
            padding-bottom: 15px;
        }
        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .content-box h3 {
            color: #2d3748;
            margin-bottom: 10px;
        }
        .content-box p {
            color: #4a5568;
            line-height: 1.6;
        }
        .education-table {
            width: 100%;
            border-collapse: collapse;
            color: black;
        }
        
        /* New styles for the added sections */
        .education-item, .experience-item {
            margin-bottom: 25px;
            display: flex;
        }
        .year-column {
            width: 120px;
            flex-shrink: 0;
            font-weight: 600;
            color: #2563eb;
            padding-top: 5px;
        }
        .details-column {
            flex-grow: 1;
            margin-left: 40%; /* This pushes the content to start from center */
            padding-right: 20px;
        }
        .degree, .role {
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
            font-size: 18px;
        }
        .institution, .company {
            color: #4a5568;
            font-style: italic;
            margin-bottom: 5px;
        }
        .description {
            color: #4a5568;
            line-height: 1.5;
            margin-top: 8px;
            font-size: 14px;
        }
        .skills-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }
        .skill-category {
            margin-bottom: 20px;
        }
        .skill-category h4 {
            color: #2563eb;
            margin-bottom: 10px;
            font-size: 18px;
        }
        .skill-list {
            list-style-type: none;
        }
        .skill-list li {
            margin-bottom: 8px;
            position: relative;
            padding-left: 20px;
            color: #4a5568;
        }
        .skill-list li:before {
            content: "•";
            color: #2563eb;
            font-weight: bold;
            position: absolute;
            left: 0;
            font-size: 18px;
        }
        hr {
            border: 0;
            height: 1px;
            background: linear-gradient(to right, transparent, #2563eb, transparent);
            margin-bottom: 25px;
        }
        
        /* NEW STYLES FOR RIGHT-ALIGNED SKILLS */
        .skills-item {
            display: flex;
            margin-bottom: 25px;
        }
        .skills-year-column {
            width: 120px;
            flex-shrink: 0;
        }
        .skills-details-column {
            flex-grow: 1;
            margin-left: 40%;
            padding-right: 20px;
        }
        
        @media (max-width: 768px) {
            .parentDiv1 {
                flex-direction: column;
                text-align: center;
                gap: 25px;
            }
            .content-grid {
                grid-template-columns: 1fr;
            }
            .skills-container {
                grid-template-columns: 1fr;
            }
            .education-item, .experience-item, .skills-item {
                flex-direction: column;
            }
            .year-column, .skills-year-column {
                width: 100%;
                margin-bottom: 5px;
            }
            .details-column, .skills-details-column {
                margin-left: 0;
                padding-right: 0;
            }
        }
    </style>
</head>
<body>
    <?php
    $name = "Jordan Marcus A. Cuison";
    $career = "BSIT Student";
    $phone = '09163057627';
    $address = "Dagupan City";
    $email = "jcuison_19ur0299@psu.edu.ph";
    $birthdate = "January 8, 1997";
    $linkedIn = "linkedin.com/in/jordan-cuison";
    $gender = "Male";
    $github = "github.com/JordanMC8";
    $nationality = "Filipino";
    $contentSection = "Creative Front-End Developer specializing in transforming complex designs into fast, responsive, and intuitive websites. A strong advocate for accessibility and performance, with a proven track record of increasing user engagement and satisfaction through pixel-perfect implementation.";
    $elementary_year = "2003 - 2009";
    $elementary_school = "University of Pangasinan";
    $highschool_year = "2009 - 2015";
    $highschool_school = "Lyceum Northwestern University General Highschool";
    $college_year = "2019 - Present";
    $college_school = "Pangasinan State University";
    $experience_year = "2016 - 2017";
    $experience_role = "Real Estate Agent";
    $experience_company = "Metro Realty Corp";
    $experience_desc = "Conducted targeted web research to extract and compile critical real estate data from multiple online sources, including property listings, market trends, and competitor information.";
    $skill1 = "Flutter&Dart";
    $skill2 = "C#";
    $skill3 = "Javascript";   

     ?>
    <div class="resume-container">
        <div class="parentDiv1">
            <div class="resumePhoto">
                <!--<img src="./photo/karina.jpg" alt ="Resume Photo"> -->
                <img src="https://placehold.co/160x160/2563eb/white?text=JC&font=arial" alt="Resume Photo">
            </div>
            <div class="personalInfo">
                <h1 class="name"><?php echo $name; ?></h1>
                <div class="career"><?php echo $career; ?></div>
                
                <table class="contact-table">
                    <tr>
                        <td>Phone: <?php echo $phone; ?></td>
                        <td>Address: <?php echo $address; ?></td>
                    </tr>
                    <tr>
                        <td>Email: <?php echo $email; ?></td>
                        <td>Date of Birth: <?php echo $birthdate; ?></td>
                    </tr>
                    <tr>
                        <td>LinkedIn: <?php echo $linkedIn; ?></td>
                        <td>Gender: <?php echo $gender; ?></td>
                    </tr>
                    <tr>
                        <td>GitHub: <?php echo $github; ?></td>
                        <td>Nationality: <?php echo $nationality; ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="content-section">
            <p><?php echo $contentSection; ?></p>
        </div>
        
        <!-- Education Section -->
        <div class="content-section">
            <h2 class="section-title">Education</h2>
            <hr>
            
            <div class="education-item">
                <div class="year-column"><?php echo $college_year?></div>
                <div class="details-column">
                    <div class="degree">Bachelor of Science in Information Technology</div>
                    <div class="institution"><?php echo $college_school?></div>
                </div>
            </div>
            
            <div class="education-item">
                <div class="year-column"><?php echo $highschool_year?></div>
                <div class="details-column">
                    <div class="degree">High School Diploma</div>
                    <div class="institution"><?php echo $highschool_school?></div>
                </div>
            </div>
            
            <div class="education-item">
                <div class="year-column"><?php echo $elementary_year?></div>
                <div class="details-column">
                    <div class="degree">Elementary Education</div>
                    <div class="institution"><?php echo $elementary_school?></div>
                </div>
            </div>
        </div>
    
        <!-- Experience Section -->
        <div class="content-section">
            <h2 class="section-title">Experience</h2>
            <hr>
            <div class="experience-item">
                <div class="year-column"><?php echo $experience_year?></div>
                <div class="details-column">
                    <div class="role"><?php echo $experience_role?></div>
                    <div class="company"><?php echo $experience_company?></div>
                    <div class="description"><?php echo $experience_desc?></div>
                </div>
            </div>
        </div>
        
        <!-- Skills Section -->
        <div class="content-section">
            <h2 class="section-title">Skills</h2>
            <hr>
     
            <div class="skills-item">
                <div class="skills-year-column"></div>
                <div class="skills-details-column">
                    <div class="skills-container">
                        <div class="skill-category">
                            <ul class="skill-list">
                                <li><?php echo $skill1 ?></li>
                                <li><?php echo $skill2 ?></li>
                                <li><?php echo $skill3 ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>