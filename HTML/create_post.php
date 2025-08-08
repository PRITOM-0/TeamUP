<?php
    session_start();
    include '../PHP/connection.php';

    // Ensure the user is logged in before they can see the page
    if (!isset($_SESSION['uid'])) {
        header("Location: ../HTML/login.html");
        exit();
    }

    // Check if the form was submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id = $_SESSION['uid'];

        $project_title = $_POST['project_title'];
        $project_description = $_POST['project_description'];
        $team_size = $_POST['team_size'];
        $contact_email = $_POST['contact_email'];

        $skills_array = isset($_POST['skills']) ? (array) $_POST['skills'] : [];
        $other_skills = isset($_POST['other_skills']) && !empty($_POST['other_skills']) ? $_POST['other_skills'] : '';

        $all_skills = $skills_array;
        if (!empty($other_skills)) {
            $other_skills_array = array_map('trim', explode(',', $other_skills));
            $all_skills = array_merge($all_skills, $other_skills_array);
        }
        
        $required_skills_string = implode(", ", $all_skills);

        $sql = "INSERT INTO posts (user_id, project_title, project_description, required_skills, team_size, contact_email) VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "isssis", $user_id, $project_title, $project_description, $required_skills_string, $team_size, $contact_email);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: dashboard.php?post_success=true");
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }

        mysqli_stmt_close($stmt);
        mysqli_close($con);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post - TeamUp DIU</title>
    <link rel="stylesheet" href="../CSS/login.css"> <!-- Use login.css for the correct header style -->
    <link rel="stylesheet" href="../CSS/create_post.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Corrected Navigation Header to match homepage style -->
    <header class="header">
        <nav class="nav-container">
            <div class="nav-brand">
                <a href="dashboard.php" class="brand-logo">
                    <img src="../img/TeamUp.png" alt="TeamUp DIU Logo" class="logo-icon">
                    <span class="logo-text">TeamUp DIU</span>
                </a>
            </div>
            <div class="nav-links">
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="../PHP/logout.php" class="nav-cta">Logout</a>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <div class="post-container">
            <div class="post-header">
                <h1>Create a New Post</h1>
                <p>Let others know what you're looking for in a teammate.</p>
            </div>

            <form class="post-form" action="create_post.php" method="POST">
                <div class="form-group">
                    <label for="project_title" class="form-label">Project Title</label>
                    <input type="text" id="project_title" name="project_title" class="form-input" placeholder="e.g., E-commerce Website" required>
                </div>

                <div class="form-group">
                    <label for="project_description" class="form-label">Project Description</label>
                    <textarea id="project_description" name="project_description" class="form-textarea" rows="4" placeholder="Briefly describe your project and what you need help with." required></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Required Skills</label>
                    <div class="skills-checkbox-grid">
                        <label><input type="checkbox" name="skills[]" value="C"> C</label>
                        <label><input type="checkbox" name="skills[]" value="C++"> C++</label>
                        <label><input type="checkbox" name="skills[]" value="Java"> Java</label>
                        <label><input type="checkbox" name="skills[]" value="Python"> Python</label>
                        <label><input type="checkbox" name="skills[]" value="JavaScript"> JavaScript</label>
                        <label><input type="checkbox" name="skills[]" value="HTML"> HTML</label>
                        <label><input type="checkbox" name="skills[]" value="CSS"> CSS</label>
                        <label><input type="checkbox" name="skills[]" value="PHP"> PHP</label>
                        <label><input type="checkbox" name="skills[]" value="SQL"> SQL</label>
                        <label><input type="checkbox" name="skills[]" value="React"> React</label>
                        <label><input type="checkbox" name="skills[]" value="Node.js"> Node.js</label>
                        <label><input type="checkbox" name="skills[]" value="MongoDB"> MongoDB</label>
                        <label><input type="checkbox" name="skills[]" value="Firebase"> Firebase</label>
                        <label><input type="checkbox" name="skills[]" value="Flutter"> Flutter</label>
                        <label><input type="checkbox" name="skills[]" value="Swift"> Swift</label>
                        <label><input type="checkbox" name="skills[]" value="Kotlin"> Kotlin</label>
                        <label><input type="checkbox" name="skills[]" value="UI/UX Design"> UI/UX Design</label>
                        <label><input type="checkbox" name="skills[]" value="Graphic Design"> Graphic Design</label>
                        <label><input type="checkbox" name="skills[]" value="Video Editing"> Video Editing</label>
                        <label><input type="checkbox" name="skills[]" value="Content Writing"> Content Writing</label>
                        <label><input type="checkbox" name="skills[]" value="Marketing"> Marketing</label>
                        <label><input type="checkbox" name="skills[]" value="Communication"> Communication</label>
                        <label><input type="checkbox" name="skills[]" value="Teamwork"> Teamwork</label>
                        <label><input type="checkbox" name="skills[]" value="Problem Solving"> Problem Solving</label>
                        <label><input type="checkbox" name="skills[]" value="Web Development"> Web Development</label>
                        <label><input type="checkbox" name="skills[]" value="App Development"> App Development</label>
                        <label><input type="checkbox" name="skills[]" value="Competitive Programming"> Competitive Programming</label>
                    </div>
                    <div class="other-skill-input">
                        <label for="other_skills" class="form-label">Other Skills:</label>
                        <input type="text" id="other_skills" name="other_skills" class="form-input" placeholder="Add any other skills (comma-separated)">
                    </div>
                </div>

                <div class="form-group">
                    <label for="team_size" class="form-label">Number of Teammates Needed</label>
                    <input type="number" id="team_size" name="team_size" class="form-input" min="1" max="10" value="1" required>
                </div>

                <div class="form-group">
                    <label for="contact_email" class="form-label">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-input" placeholder="Your best contact email" required>
                </div>

                <input type="submit" class="submit-button" value="Publish Post">
            </form>
        </div>
    </main>
</body>
</html>
