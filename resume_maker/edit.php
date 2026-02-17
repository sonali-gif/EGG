<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('Invalid resume id');
}

$id = intval($_GET['id']);
$conn = new mysqli("localhost","root","","resume_db");
if ($conn->connect_error) die('DB error');

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, email, phone, skills, experience FROM resumes WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
if (!$data) {
    die('Not allowed or resume not found');
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Edit Resume — <?= htmlspecialchars($data['name']) ?></title>
  <link rel="stylesheet" href="style.css">
  <style>form { max-width:700px; margin:30px auto; padding:24px; } label{display:block;margin:10px 0 6px;} .muted{color:#666; font-size:0.95em;} .nav-actions{position:absolute; right:18px; top:18px;} .save-btn{background:#28a745;}</style>
</head>
<body>
<div class="nav-actions">
  <button class="small-btn secondary" onclick="location.href='my_resumes.php'">Back</button>
</div>
<h1 style="color:white; text-align:center; margin-top:24px;">Edit Resume — <?= htmlspecialchars($data['name']) ?></h1>
<form method="POST" action="update.php" class="card" style="background:#fff; border-radius:12px;">
  <input type="hidden" name="id" value="<?= $id ?>">

  <label for="skills">Skills</label>
  <input id="skills" name="skills" value="<?= htmlspecialchars($data['skills']) ?>" placeholder="Comma separated skills" required>

  <label for="experience">Experience</label>
  <textarea id="experience" name="experience" required><?= htmlspecialchars($data['experience']) ?></textarea>

  <div style="display:flex;gap:10px;margin-top:16px;">
    <button type="submit" class="small-btn save-btn">Update</button>
    <button type="button" class="small-btn secondary" onclick="location.href='preview.html?id=<?= $id ?>'">Preview</button>
  </div>
</form>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>
