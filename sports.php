<?php
session_start();

$conn = mysqli_connect("localhost","root","","sportscoach");

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}

/* INSERT DATA */
if(isset($_POST['submit'])){

    $fullname = $_POST['fullname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $sport = $_POST['sport'];
    $experience = $_POST['experience'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    /* RESUME UPLOAD */
    $resume = time() . "_" . $_FILES['resume']['name'];
    $tmp = $_FILES['resume']['tmp_name'];
    $folder = "uploads/" . $resume;

    move_uploaded_file($tmp, $folder);

    $sql = "INSERT INTO applications
    (fullname,age,gender,sport,experience,email,phone,address,resume)
    VALUES
    ('$fullname','$age','$gender','$sport','$experience','$email','$phone','$address','$resume')";

    if(mysqli_query($conn,$sql)){
        $_SESSION['msg'] = "Application Submitted Successfully!";
        header("Location: sports.php");
        exit();
    }
}

/* FETCH DATA */
$result = mysqli_query($conn,"SELECT * FROM applications");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sports Coach Job Application</title>

<style>
body{
    margin:0;
    font-family:Arial;
    background:#f5f7fa;
}

/* NAVBAR */
.navbar{
    background:#001d3d;
    display:flex;
    justify-content:space-between;
    padding:15px 40px;
    position:sticky;
    top:0;
}

.logo{
    color:white;
    font-size:26px;
    font-weight:bold;
}

.navbar ul{
    list-style:none;
    display:flex;
    gap:20px;
}

.navbar ul li a{
    color:white;
    text-decoration:none;
}

/* HERO */
.hero{
    height:450px;
    background:url('https://images.unsplash.com/photo-1517649763962-0c623066013b') center/cover;
    display:flex;
    align-items:center;
    justify-content:center;
}

.overlay{
    background:rgba(0,0,0,0.6);
    width:100%;
    height:100%;
    color:white;
    text-align:center;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.overlay h1{
    font-size:40px;
}

.hero-btn{
    padding:12px 25px;
    background:#ffd60a;
    border:none;
    font-weight:bold;
    cursor:pointer;
}

/* FORM */
.container{
    width:65%;
    margin:40px auto;
    background:white;
    padding:30px;
    border-radius:10px;
    box-shadow:0px 0px 10px gray;
}

input,select,textarea{
    width:100%;
    padding:10px;
    margin-top:10px;
}

button{
    width:100%;
    padding:12px;
    background:#003566;
    color:white;
    border:none;
    margin-top:15px;
}

/* TABLE */
table{
    width:100%;
    margin-top:30px;
    border-collapse:collapse;
}

table,th,td{
    border:1px solid black;
    padding:10px;
    text-align:center;
}

th{
    background:#003566;
    color:white;
}

.success{
    color:green;
    text-align:center;
    font-size:18px;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <div class="logo">SportsCoach</div>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#form">Apply</a></li>
        <li><a href="#data">Applications</a></li>
    </ul>
</div>

<!-- HERO SECTION -->
<div class="hero">
    <div class="overlay">
        <h1>Sports Coach Recruitment Portal</h1>
        <p>Build your coaching career with us</p>
        <a href="#form"><button class="hero-btn">Apply Now</button></a>
    </div>
</div>

<!-- SUCCESS MESSAGE -->
<?php
if(isset($_SESSION['msg'])){
    echo "<h3 class='success'>".$_SESSION['msg']."</h3>";
    unset($_SESSION['msg']);
}
?>

<!-- FORM -->
<div class="container" id="form">

<h2>Sports Coach Application Form</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="fullname" placeholder="Full Name" required>
<input type="number" name="age" placeholder="Age" required>

<select name="gender" required>
<option>Male</option>
<option>Female</option>
</select>

<select name="sport" required>
<option>Football</option>
<option>Cricket</option>
<option>Basketball</option>
<option>Volleyball</option>
</select>

<input type="text" name="experience" placeholder="Experience" required>
<input type="email" name="email" placeholder="Email" required>
<input type="text" name="phone" placeholder="Phone" required>
<textarea name="address" placeholder="Address"></textarea>

<!-- RESUME UPLOAD -->
<label style="margin-top:10px;display:block;font-weight:bold;">
    Upload Resume (PDF / DOC / DOCX)
</label>

<input type="file" name="resume" required>

<button type="submit" name="submit">Submit Application</button>

</form>

</div>

<!-- TABLE -->
<div class="container" id="data">

<h2>Submitted Applications</h2>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Sport</th>
<th>Experience</th>
<th>Email</th>
<th>Resume</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['fullname']; ?></td>
<td><?php echo $row['sport']; ?></td>
<td><?php echo $row['experience']; ?></td>
<td><?php echo $row['email']; ?></td>
<td>
<a href="uploads/<?php echo $row['resume']; ?>" target="_blank">
View Resume
</a>
</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>