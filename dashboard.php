<?php
require_once 'config.php';
requireLogin();

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['name'];

// Handle Clock In
if (isset($_POST['clock_in'])) {
    $stmt = $conn->prepare("INSERT INTO time_entries (user_id, clock_in) VALUES (?, NOW())");
    $stmt->execute([$user_id]);
    header('Location: dashboard.php');
    exit();
}

// handle clock out
if (isset($_POST['clock_out'])) {
    $stmt = $conn->prepare("UPDATE time_entries SET clock_out = NOW() WHERE user_id = ? AND clock_out IS NULL");
    $stmt->execute([$user_id]);
    header('Location: dashboard.php');
    exit();
}

// check cirrent status; who clocked in/out
$stmt = $conn->prepare("SELECT * FROM time_entries WHERE user_id = ? AND clock_out IS NULL ORDER BY clock_in DESC LIMIT 1");
$stmt->execute([$user_id]);
$current_entry = $stmt->fetch();
$is_clocked_in = !empty($current_entry);

// get this week's time entries
$stmt = $conn->prepare("
                        SELECT DATE(clock_in) as date,
                        clock_in, clock_out,
                        TIMESTAMPDIFF(MINUTE, clock_in, COALESCE(clock_out, NOW())) as minutes
                    FROM time_entries
                    WHERE user_id = ? 
                    AND clock_in >= DATE_SUB(CURDATE(), INTERVAL WEEKDAY(CURDATE()) DAY)
                    ORDER BY clock_in DESC
                    ");
$stmt->execute([$user_id]);
$time_entries = $stmt->fetchAll(PDO::FETCH_ASSOC);

// calculate total hours this week
$total_minutes = 0;
foreach ($time_entries as $entry) {
    if ($entry['clock_out']) {
        $total_minutes += $entry['minutes']; // only count completed entries
    }
}

$total_hours = floor($total_minutes / 60);
$remaining_minutes = $total_minutes % 60;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Time Tracker</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header">
            <h1>Time Tracker</h1>
            <div class="user-info">
                <span><?php echo htmlspecialchars($user_name); ?></span>
                <a href="logout.php" class="btn btn-small">Sign Out</a>
            </div>
        </header>

        <div class="clock-section">
            <div class="current-status">
                <?php if ($is_clocked_in): ?>
                    <div class="status-badge clocked-in">Currently Working</div>
                    <p>Started at <?php echo date('g:i A', strtotime($current_entry['clock_in'])); ?></p>
                <?php else: ?>
                    <div class="status-badge clocked-out">Not Working</div>
                    <p>Ready to clock in</p>
                <?php endif; ?>
            </div>

            <form method="POST" action="dashboard.php">
                <?php if ($is_clocked_in): ?>
                    <button type="submit" name="clock_out" class="btn btn-danger btn-large">Clock Out</button>
                <?php else: ?>
                    <button type="submit" name="clock_in" class="btn btn-success btn-large">Clock In</button>
                <?php endif; ?>
            </form>
        </div>

        <div class="summary-section">
            <h2>This Week</h2>
            <div class="hours-card">
                <div class="hours-number"><?php echo $total_hours; ?>h <?php echo $remaining_minutes; ?>m</div>
                <div class="hours-label">Total Hours Logged</div>
            </div>
        </div>

        <div class="timesheet-section">
            <h2>Recent Activity</h2>
            <?php if (empty($time_entries)): ?>
                <p class="no-data">No entries yet. Clock in to get started.</p>
            <?php else: ?>
                <table class="timesheet-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>In</th>
                            <th>Out</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($time_entries as $entry): ?>
                            <tr>
                                <td><?php echo date('M j, Y', strtotime($entry['date'])); ?></td>
                                <td><?php echo date('g:i A', strtotime($entry['clock_in'])); ?></td>
                                <td>
                                    <?php 
                                    if ($entry['clock_out']) {
                                        echo date('g:i A', strtotime($entry['clock_out']));
                                    } else {
                                        echo '<span class="active-badge">Active</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    if ($entry['clock_out']) {
                                        $hours = floor($entry['minutes'] / 60);
                                        $mins = $entry['minutes'] % 60;
                                        echo "{$hours}h {$mins}m";
                                    } else {
                                        echo '—';
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>