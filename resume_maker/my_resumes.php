<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
$conn = new mysqli("localhost","root","","resume_db");
if ($conn->connect_error) {
    die('Database connection failed');
}
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, email, phone, skills, experience, created_at FROM resumes WHERE user_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>My Resumes</title>
<link rel="stylesheet" href="style.css">
<style>
.resume-actions { display:flex; gap:8px; margin-top:12px; align-items:center; }
.action-btn { display:inline-block; padding:10px 14px; border-radius:8px; text-decoration:none; color:#fff; background:#667eea; font-weight:600; transition:background .15s; }
.action-btn:hover { background:#5563d6; }
.small-btn { padding:8px 10px; border-radius:8px; border:none; cursor:pointer; background:#667eea; color:#fff; font-weight:600; }
.small-btn.secondary { background:#f1f1f1; color:#333; border:1px solid #ccc; }
.empty { color:#fff; opacity:0.9; margin-top:40px; }
.card-meta { color:#999; font-size:0.9em; margin-top:6px; }
.card-snippet { color:#555; font-size:0.95em; margin-top:10px; white-space:pre-wrap; max-height:48px; overflow:hidden; }
.navbar { margin-bottom:20px; }
.flash { background: rgba(255,255,255,0.12); color: #fff; padding:10px 14px; border-radius:8px; display:inline-block; margin:10px 0; }
</style>
</head>
<body>
<div class="navbar">
    <h1>📝 Resume Maker</h1>
    <div style="position:absolute;right:20px;top:22px;">
        <button class="small-btn secondary" onclick="location.href='index.html'">Dashboard</button>
        <button class="small-btn" onclick="location.href='form.html'">Create</button>
        <button class="small-btn secondary" onclick="location.href='logout.php'">Logout</button>
    </div>
</div>

<h2 style="color:white;">My Resumes</h2>

<?php if (isset($_GET['updated'])): ?>
    <div class="flash"><?= $_GET['updated'] === '1' ? '✅ Resume updated successfully.' : '⚠️ Update failed.' ?></div>
<?php endif; ?>

<div class="template-container" style="padding:20px;">
<?php if ($result->num_rows === 0): ?>
    <p class="empty">You haven't created any resumes yet. <a href="form.html" style="color:#fff;text-decoration:underline;">Create one now</a>.</p>
<?php else: ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="template-card" style="width:320px;">
            <h2><?=htmlspecialchars($row['name'])?></h2>
            <p style="color:#666; margin-bottom:6px;"><?=htmlspecialchars($row['email'])?> • <?=htmlspecialchars($row['phone'])?></p>
            <div class="card-meta">Saved: <?= htmlspecialchars($row['created_at'] ?? '') ?></div>
            <div class="card-snippet"><?= htmlspecialchars(mb_strimwidth($row['skills'] . "\n" . $row['experience'], 0, 140, '...')) ?></div>
            <div class="resume-actions">
                <a class="action-btn" href="preview.html?id=<?= $row['id'] ?>">View</a>
                <a class="action-btn" href="edit.php?id=<?= $row['id'] ?>">Edit</a>
                <button class="small-btn secondary" onclick="downloadResume(<?= $row['id'] ?>)">Download</button>
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
</div>

<script>
function downloadResume(id){
    // open preview page and let user download from there
    window.open('preview.html?id='+id, '_blank');
}
</script>

</body>
</html>
