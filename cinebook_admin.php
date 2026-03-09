<?php
// cinebook_admin.php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    // Not logged in as admin, show login screen
    $showLogin = true;
} else {
    // Admin is logged in
    $showLogin = false;
    $admin_id = $_SESSION['user_id'];
    $admin_username = $_SESSION['username'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes">
<title>CineBook Admin — Modern Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    /* ALL EXISTING CSS STYLES - KEEP EXACTLY AS IS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    html, body {
        font-family: 'Inter', sans-serif;
        background: #0a0a0f;
        color: #ffffff;
        min-height: 100vh;
        line-height: 1.5;
    }

    :root {
        --bg-primary: #0a0a0f;
        --bg-secondary: #12121a;
        --bg-tertiary: #1e1e2a;
        --bg-card: #1a1a24;
        --bg-card-hover: #252530;
        --text-primary: #ffffff;
        --text-secondary: #a0a0b0;
        --accent-primary: #ff8c42;
        --accent-secondary: #1e3a8a;
        --accent-gradient: linear-gradient(135deg, #1e3a8a, #ff8c42);
        --accent-glow: 0 0 20px rgba(30, 58, 138, 0.3);
        --accent-yellow: #ffd166;
        --accent-green: #06d6a0;
        --accent-blue: #4cc9f0;
        --border-color: rgba(255, 255, 255, 0.08);
        --shadow-color: rgba(0, 0, 0, 0.5);
        --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.3);
        --seat-available: #2a2a3a;
        --seat-selected: #ff8c42;
        --seat-occupied: #1e3a8a;
        --rating-g: #06d6a0;
        --rating-pg: #4cc9f0;
        --rating-pg13: #ffd166;
        --rating-r: #ff8c42;
        --rating-nc17: #1e3a8a;
        --glass-effect: rgba(255, 255, 255, 0.03);
        --glass-border: rgba(255, 255, 255, 0.05);
        --success: #06d6a0;
        --warning: #ffd166;
        --danger: #ff8c42;
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --blur: blur(20px);
    }

    /* Mobile App Container */
    .app-container {
        max-width: 480px;
        margin: 0 auto;
        background: var(--bg-primary);
        min-height: 100vh;
        position: relative;
        box-shadow: var(--shadow-elevation);
    }

    /* Header */
.app-header {
    background: var(--accent-gradient);
    color: white;
    padding: 16px 20px;
    position: sticky;
    top: 0;
    z-index: 100;
    border-bottom-left-radius: 24px;
    border-bottom-right-radius: 24px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    margin-bottom: 16px;
}

.header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.header-top h1 {
    font-size: 1.4rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    letter-spacing: -0.5px;
    color: white;
    margin: 0;
}

.header-top h1 i {
    font-size: 1.6rem;
    filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
}

.logout-btn {
    background: rgba(255, 255, 255, 0.15);
    color: white;
    padding: 8px 14px;
    border-radius: 40px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    backdrop-filter: var(--blur);
    border: 1px solid rgba(255, 255, 255, 0.2);
    cursor: pointer;
    transition: var(--transition);
}

.logout-btn:hover, .logout-btn:active {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

    /* Welcome Section */
.welcome-section {
    margin-top: 16px;
    padding-top: 8px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
}

.welcome-title {
    font-size: 1.6rem;
    font-weight: 700;
    margin-bottom: 6px;
    line-height: 1.2;
    color: white;
}

.welcome-subtitle {
    font-size: 0.9rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    color: rgba(255, 255, 255, 0.9);
}

.date-badge {
    background: rgba(255, 255, 255, 0.15);
    padding: 4px 12px;
    border-radius: 40px;
    font-size: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

    /* Bottom Navigation */
    .bottom-nav {
        position: sticky;
        bottom: 16px;
        left: 0;
        right: 0;
        margin: 0 auto;
        width: fit-content;
        max-width: 90%;
        background: rgba(18, 18, 26, 0.9);
        backdrop-filter: var(--blur);
        -webkit-backdrop-filter: var(--blur);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2px;
        padding: 6px 10px;
        border-radius: 100px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--glass-border);
        z-index: 100;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 2px;
        color: var(--text-secondary);
        font-size: 0.6rem;
        font-weight: 500;
        cursor: pointer;
        padding: 6px 8px;
        border-radius: 100px;
        transition: var(--transition);
        white-space: nowrap;
        min-width: 40px;
    }

    .nav-item i {
        font-size: 1.1rem;
        transition: var(--transition);
    }

    .nav-item.active {
        color: white;
        background: var(--accent-gradient);
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3);
    }

    .nav-item.active i {
        transform: scale(1.1);
        filter: drop-shadow(0 4px 8px rgba(255, 140, 66, 0.4));
    }

    /* Main Content */
    .main-content {
        padding: 20px 16px;
        min-height: calc(100vh - 60px);
        background: var(--bg-primary);
    }

    /* Screen Container */
    .screen {
        display: none;
        animation: fadeIn 0.3s ease-in-out;
    }

    .screen.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        overflow-x: auto;
        padding: 4px 0;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }

    .quick-actions::-webkit-scrollbar {
        display: none;
    }

    .quick-action-item {
        background: var(--bg-card);
        padding: 10px 16px;
        border-radius: 100px;
        display: flex;
        align-items: center;
        gap: 6px;
        box-shadow: var(--shadow-elevation);
        border: 1px solid var(--glass-border);
        white-space: nowrap;
        transition: var(--transition);
        cursor: pointer;
        color: var(--text-primary);
        font-size: 0.8rem;
    }

    .quick-action-item:hover {
        background: var(--accent-gradient);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(30, 58, 138, 0.4);
    }

    .quick-action-item i {
        color: var(--accent-primary);
        font-size: 0.9rem;
        transition: var(--transition);
    }

    .quick-action-item:hover i {
        color: white;
    }

    /* Stats Slider/Carousel */
    .stats-slider-container {
        position: relative;
        margin-bottom: 24px;
        padding: 0 4px;
    }

    .stats-slider {
        display: flex;
        gap: 16px;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        -ms-overflow-style: none;
        padding: 8px 4px 16px 4px;
    }

    .stats-slider::-webkit-scrollbar {
        display: none;
    }

    .stat-slide {
        flex: 0 0 auto;
        width: calc(100% - 32px);
        max-width: 280px;
        scroll-snap-align: start;
        transition: var(--transition);
    }

    .stat-card {
        background: var(--accent-gradient);
        color: white;
        padding: 24px;
        border-radius: 24px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3);
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        cursor: pointer;
        height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .stat-card:hover {
        transform: translateY(-4px) scale(1.02);
        box-shadow: 0 30px 60px rgba(30, 58, 138, 0.4);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0.5;
        animation: rotate 20s linear infinite;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    .stat-icon {
        font-size: 2.5rem;
        margin-bottom: 12px;
        position: relative;
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }

    .stat-card .number {
        font-size: 2.5rem;
        font-weight: 800;
        margin-bottom: 8px;
        line-height: 1;
        position: relative;
        text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .stat-card .label {
        font-size: 1rem;
        opacity: 0.9;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        font-weight: 500;
        position: relative;
    }

    /* Slider Indicators */
    .slider-indicators {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 12px;
    }

    .indicator {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--text-secondary);
        transition: var(--transition);
        cursor: pointer;
    }

    .indicator.active {
        width: 24px;
        background: var(--accent-primary);
        border-radius: 12px;
    }

    /* Slider Navigation Buttons */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--bg-card);
        box-shadow: var(--shadow-elevation);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: var(--transition);
        border: 1px solid var(--glass-border);
        color: var(--accent-primary);
    }

    .slider-nav:hover {
        background: var(--accent-gradient);
        color: white;
        transform: translateY(-50%) scale(1.1);
    }

    .slider-nav.prev {
        left: -12px;
    }

    .slider-nav.next {
        right: -12px;
    }

    /* Featured Section */
    .featured-section {
        margin-bottom: 24px;
    }

    .section-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .section-title h2 {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title h2 i {
        color: var(--accent-primary);
    }

    .view-all {
        color: var(--accent-primary);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .view-all:hover {
        color: var(--accent-secondary);
        transform: translateX(4px);
    }

    /* Featured Movie Card */
    .featured-movie {
        background: var(--accent-gradient);
        border-radius: 24px;
        padding: 16px;
        color: white;
        display: flex;
        gap: 16px;
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3);
    }

    .featured-movie-poster {
        width: 80px;
        height: 120px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        flex-shrink: 0;
    }

    .featured-movie-info {
        flex: 1;
    }

    .featured-movie-info h3 {
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 4px;
        color: white;
    }

    .featured-movie-meta {
        display: flex;
        gap: 8px;
        margin-bottom: 8px;
        font-size: 0.8rem;
        opacity: 0.9;
        flex-wrap: wrap;
    }

    .featured-movie-meta span {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .featured-movie-rating {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 10px;
        border-radius: 100px;
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .featured-movie-price {
        font-size: 1.2rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
        color: white;
    }

    .featured-movie-price small {
        font-size: 0.8rem;
        opacity: 0.8;
        font-weight: normal;
    }

    /* Activity List */
    .activity-list {
        background: var(--bg-card);
        border-radius: 24px;
        padding: 16px;
        box-shadow: var(--shadow-elevation);
        border: 1px solid var(--glass-border);
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid var(--glass-border);
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        background: rgba(255, 140, 66, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--accent-primary);
        font-size: 1rem;
        flex-shrink: 0;
    }

    .activity-details {
        flex: 1;
        min-width: 0;
    }

    .activity-title {
        font-weight: 600;
        margin-bottom: 2px;
        color: var(--text-primary);
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .activity-time {
        font-size: 0.7rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .activity-amount {
        font-weight: 700;
        color: var(--accent-green);
        font-size: 0.9rem;
        white-space: nowrap;
    }

    /* Section Cards */
    .section-card {
        background: var(--bg-card);
        backdrop-filter: var(--blur);
        border-radius: 32px;
        margin-bottom: 24px;
        padding: 20px;
        box-shadow: var(--shadow-elevation);
        border: 1px solid var(--glass-border);
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 2px solid rgba(255, 140, 66, 0.2);
    }

    .section-header h3 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: -0.3px;
    }

    .section-header h3 i {
        color: var(--accent-primary);
    }

    .badge {
        background: var(--accent-gradient);
        color: white;
        padding: 4px 10px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(30, 58, 138, 0.3);
    }

    /* Form Styles */
    .mobile-form {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 6px;
        letter-spacing: -0.2px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        padding: 12px 14px;
        border: 2px solid var(--glass-border);
        border-radius: 16px;
        font-size: 0.9rem;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        transition: var(--transition);
    }

    .form-group input::placeholder,
    .form-group textarea::placeholder {
        color: var(--text-secondary);
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 4px rgba(255, 140, 66, 0.1);
        background: rgba(255, 255, 255, 0.1);
    }

    .btn-primary {
        background: var(--accent-gradient);
        color: white;
        padding: 14px;
        border: none;
        border-radius: 100px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3);
    }

    .btn-primary:active {
        transform: scale(0.98);
        box-shadow: 0 10px 20px rgba(30, 58, 138, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
        padding: 12px;
        border: 2px solid var(--glass-border);
        border-radius: 100px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-decoration: none;
        text-align: center;
    }

    .btn-secondary:active {
        background: rgba(255, 140, 66, 0.1);
        border-color: var(--accent-primary);
    }

    /* Movie Cards */
.movie-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 16px;
    margin-top: 20px;
}

.movie-card {
    background: var(--bg-tertiary);
    border-radius: 24px;
    padding: 16px;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.movie-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: var(--accent-gradient);
    opacity: 0.5;
}

.movie-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3);
    border-color: rgba(255, 140, 66, 0.3);
}

.movie-header {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
}

.movie-poster {
    width: 80px;
    height: 120px;
    border-radius: 16px;
    background: linear-gradient(135deg, #2a2a3a, #1a1a2a);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    box-shadow: 0 10px 20px rgba(0,0,0,0.4);
    flex-shrink: 0;
    overflow: hidden;
    border: 2px solid rgba(255, 140, 66, 0.3);
}

.movie-poster img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.movie-info {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.movie-title {
    font-size: 1.1rem;
    font-weight: 700;
    letter-spacing: -0.3px;
    color: var(--text-primary);
    line-height: 1.3;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.movie-meta {
    font-size: 0.8rem;
    color: var(--text-secondary);
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 2px;
}

.movie-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 255, 255, 0.05);
    padding: 4px 8px;
    border-radius: 20px;
}

.movie-meta span i {
    color: var(--accent-primary);
    font-size: 0.7rem;
}

.rating-badge {
    background: var(--accent-gradient);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    width: fit-content;
}

.movie-price {
    font-size: 1rem;
    color: var(--accent-primary);
    font-weight: 700;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}

.movie-price i {
    font-size: 0.8rem;
    color: var(--accent-green);
}

.movie-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
    border-top: 1px solid var(--glass-border);
    padding-top: 16px;
    flex-wrap: wrap;
}

.action-btn {
    padding: 8px 14px;
    border: none;
    border-radius: 40px;
    font-size: 0.75rem;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: var(--transition);
    text-decoration: none;
    letter-spacing: 0.3px;
}

.action-btn i {
    font-size: 0.8rem;
}

.action-btn.edit {
    background: rgba(255, 140, 66, 0.15);
    color: var(--accent-primary);
    border: 1px solid rgba(255, 140, 66, 0.3);
}

.action-btn.edit:hover {
    background: rgba(255, 140, 66, 0.25);
    transform: translateY(-2px);
}

.action-btn.delete {
    background: rgba(239, 68, 68, 0.15);
    color: #ff6b6b;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.action-btn.delete:hover {
    background: rgba(239, 68, 68, 0.25);
    transform: translateY(-2px);
}

.action-btn.move {
    background: rgba(6, 214, 160, 0.15);
    color: var(--accent-green);
    border: 1px solid rgba(6, 214, 160, 0.3);
}

.action-btn.move:hover {
    background: rgba(6, 214, 160, 0.25);
    transform: translateY(-2px);
}

.action-btn.trailer {
    background: rgba(255, 209, 102, 0.15);
    color: var(--accent-yellow);
    border: 1px solid rgba(255, 209, 102, 0.3);
}

.action-btn.trailer:hover {
    background: rgba(255, 209, 102, 0.25);
    transform: translateY(-2px);
}

    .action-btn.view {
        background: rgba(76, 201, 240, 0.1);
        color: var(--accent-blue);
    }

.coming-soon-badge i {
    font-size: 0.6rem;
}

.release-date {
    font-size: 0.75rem;
    color: var(--accent-primary);
    display: flex;
    align-items: center;
    gap: 4px;
    background: rgba(255, 140, 66, 0.1);
    padding: 4px 10px;
    border-radius: 20px;
    width: fit-content;
}

.release-date i {
    font-size: 0.65rem;
}

/* Empty state improvements */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--text-secondary);
    background: var(--bg-tertiary);
    border-radius: 24px;
    border: 2px dashed var(--glass-border);
}

.empty-state i {
    font-size: 3.5rem;
    margin-bottom: 16px;
    color: var(--accent-primary);
    opacity: 0.3;
}

.empty-state p {
    font-size: 0.95rem;
    font-weight: 500;
}

    /* Seat Layout */
    .seat-container {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        margin: 16px 0;
        padding: 12px 0;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 20px;
        position: relative;
        touch-action: pan-x pan-y pinch-zoom;
        cursor: grab;
        user-select: none;
        -webkit-user-select: none;
    }
    
    .seat-container:active {
        cursor: grabbing;
    }

    .zoom-wrapper {
        min-width: max-content;
        padding: 8px;
        transform-origin: top left;
        transition: transform 0.1s ease;
    }

    .seat-grid {
        display: flex;
        flex-direction: column;
    }

    .seat-row-container {
        display: flex;
        align-items: center;
        margin: 4px 0;
        gap: 4px;
    }

    .row-label-left, .row-label-right {
        width: 24px;
        text-align: center;
        font-weight: 700;
        color: var(--accent-primary);
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .seat-row {
        display: flex;
        gap: 2px;
        align-items: center;
    }

    .seat {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        text-align: center;
        line-height: 24px;
        font-size: 0.65rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .seat.available {
        background: var(--seat-available);
        border: 1px solid transparent;
        color: var(--text-primary);
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .seat.available:hover {
        border-color: var(--accent-primary);
        transform: scale(1.1);
    }

    .seat.booked {
        background: var(--seat-occupied);
        color: white;
        cursor: not-allowed;
        opacity: 0.7;
    }

    .seat.blocked {
        background: linear-gradient(135deg, #ff3b6f, #d32f2f);
        color: white;
    }

    .seat.selected {
        background: var(--seat-selected);
        color: white;
        border-color: white;
        box-shadow: 0 0 20px rgba(255, 142, 83, 0.5);
    }

    /* Screen Display */
    .screen-display {
        width: 100%;
        margin: 20px 0 10px 0;
        background: linear-gradient(180deg, #2a2a3a, #1a1a2a);
        color: var(--accent-yellow);
        text-align: center;
        padding: 10px 0;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 3px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
        border: 1px solid var(--glass-border);
        text-transform: uppercase;
    }

    /* Zoom Controls */
    .zoom-controls {
        display: flex;
        gap: 8px;
        background: var(--bg-card);
        padding: 6px 12px;
        border-radius: 40px;
        border: 1px solid var(--glass-border);
        box-shadow: var(--shadow-elevation);
        z-index: 100;
        width: fit-content;
        margin: 0 auto 16px;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .zoom-btn {
        width: 32px;
        height: 32px;
        border-radius: 32px;
        background: var(--accent-gradient);
        color: white;
        border: none;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(30, 58, 138, 0.3);
    }

    .zoom-btn:active {
        transform: scale(0.95);
    }

    .zoom-level {
        color: var(--text-primary);
        font-weight: 600;
        min-width: 45px;
        text-align: center;
        line-height: 32px;
        font-size: 0.8rem;
    }

    /* Filter Bar */
    .filter-bar {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: var(--blur);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 16px;
        border: 1px solid var(--glass-border);
    }

    .filter-group {
        margin-bottom: 12px;
    }

    .filter-group label {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 4px;
        display: block;
        color: var(--text-primary);
    }

    .filter-group select,
    .filter-group input {
        width: 100%;
        padding: 10px 12px;
        border: 2px solid var(--glass-border);
        border-radius: 12px;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.05);
        color: var(--text-primary);
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: var(--blur);
        z-index: 1000;
        padding: 16px;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: var(--bg-card);
        border-radius: 28px;
        width: 100%;
        max-width: 400px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.4);
        border: 1px solid var(--glass-border);
        animation: modalSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    @keyframes modalSlide {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .modal-header {
        background: var(--accent-gradient);
        color: white;
        padding: 20px;
        border-radius: 28px 28px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-size: 1.1rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: white;
    }

    .close-modal {
        background: rgba(255, 255, 255, 0.2);
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
    }

    .close-modal:hover {
        background: rgba(255, 140, 66, 0.3);
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 20px;
    }

    /* Trailer Modal */
    .trailer-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 16px;
        background: #000;
    }

    .trailer-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border: none;
    }

    /* Toast */
    .toast {
        position: fixed;
        bottom: 100px;
        left: 50%;
        transform: translateX(-50%);
        width: calc(100% - 32px);
        max-width: 400px;
        background: var(--bg-card);
        border-radius: 100px;
        padding: 14px 20px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        display: none;
        align-items: center;
        gap: 10px;
        border-left: 4px solid var(--accent-green);
        z-index: 1000;
        color: var(--text-primary);
        animation: toastSlide 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--glass-border);
        font-size: 0.9rem;
    }

    @keyframes toastSlide {
        from {
            opacity: 0;
            transform: translate(-50%, 100px);
        }
        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    .toast.show {
        display: flex;
    }

    .toast i {
        color: var(--accent-green);
    }

    /* Login Screen */
    .login-screen {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2a 100%);
        position: relative;
    }

    .login-card {
        background: rgba(18, 18, 26, 0.7);
        backdrop-filter: var(--blur);
        border-radius: 40px;
        padding: 32px 20px;
        width: 100%;
        max-width: 340px;
        text-align: center;
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.5);
        border: 1px solid var(--glass-border);
        animation: loginFade 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        z-index: 2;
    }

    @keyframes loginFade {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .login-icon {
        font-size: 3.5rem;
        color: var(--accent-primary);
        margin-bottom: 20px;
        filter: drop-shadow(0 10px 15px rgba(255, 140, 66, 0.3));
    }

    .login-card h2 {
        color: var(--text-primary);
        margin-bottom: 4px;
        font-size: 1.6rem;
        font-weight: 800;
        letter-spacing: -1px;
        background: var(--accent-gradient);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-card p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 28px;
    }

    /* Tables */
.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8rem;
    color: var(--text-primary);
}

.data-table th {
    text-align: left;
    padding: 12px 8px;
    background: var(--accent-gradient);
    color: white;
    font-weight: 600;
    font-size: 0.8rem;
}

.data-table th:first-child {
    border-radius: 12px 0 0 12px;
}

.data-table th:last-child {
    border-radius: 0 12px 12px 0;
}

.data-table td {
    padding: 10px 8px;
    border-bottom: 1px solid var(--glass-border);
    color: var(--text-primary);
}

.data-table tr:last-child td {
    border-bottom: none;
}

.data-table td {
    padding: 10px 8px;
    border-bottom: 1px solid var(--glass-border);
    color: var(--text-primary); /* White text in dark mode */
}

/* Make specific columns more visible with different colors */
.data-table td:nth-child(2), /* Points column */
.data-table td:nth-child(3) { /* Total Earned column */
    color: var(--accent-yellow);
    font-weight: 600;
}

.data-table td:last-child { /* Date column */
    color: var(--text-secondary);
}

.data-table tr:last-child td {
    border-bottom: none;
}

    /* Legend Items */
    .legend-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    .legend-item .seat {
        width: 20px;
        height: 20px;
        line-height: 20px;
        font-size: 0.6rem;
    }

    /* Details Summary */
    details {
        margin-bottom: 16px;
    }

    details summary {
        background: rgba(255, 140, 66, 0.1);
        padding: 12px 16px;
        border-radius: 100px;
        cursor: pointer;
        font-weight: 600;
        color: var(--accent-primary);
        list-style: none;
        transition: var(--transition);
        border: 1px solid var(--glass-border);
        font-size: 0.9rem;
    }

    details summary::-webkit-details-marker {
        display: none;
    }

    details summary:hover {
        background: rgba(255, 140, 66, 0.2);
    }

    details[open] summary {
        border-radius: 100px 100px 0 0;
        margin-bottom: 12px;
    }

    details[open] summary i {
        transform: rotate(90deg);
    }

    summary i {
        transition: var(--transition);
        margin-right: 6px;
    }

    /* Movie Toggle */
    .movie-toggle {
        display: flex;
        gap: 8px;
        margin-bottom: 20px;
        background: var(--bg-secondary);
        padding: 4px;
        border-radius: 100px;
        border: 1px solid var(--glass-border);
    }
    
    .toggle-btn {
        flex: 1;
        text-align: center;
        padding: 8px 12px;
        border-radius: 100px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        color: var(--text-secondary);
        font-size: 0.85rem;
    }
    
    .toggle-btn.active {
        background: var(--accent-gradient);
        color: white;
        box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3);
    }
    
    .movie-section {
        display: none;
    }
    
    .movie-section.active {
        display: block;
    }

    /* Edit Form Container */
    .edit-form-container {
        background: var(--bg-tertiary);
        border-radius: 20px;
        padding: 16px;
        margin-bottom: 20px;
        border: 2px solid var(--accent-primary);
    }

    .edit-form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .edit-form-header h4 {
        color: var(--accent-primary);
        font-size: 1rem;
    }

    .cancel-edit {
        color: #ff3b6f;
        cursor: pointer;
        padding: 6px 12px;
        border-radius: 100px;
        background: rgba(255, 59, 111, 0.1);
        border: 1px solid rgba(255, 59, 111, 0.3);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Empty States */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 16px;
        color: var(--accent-primary);
        opacity: 0.5;
    }

    .empty-state p {
        font-size: 0.9rem;
    }

    /* Booking Summary */
    .booking-summary {
        margin-top: 16px;
        padding: 16px;
        background: rgba(6, 214, 160, 0.1);
        border-left: 4px solid var(--accent-green);
        border-radius: 16px;
        border: 1px solid rgba(6, 214, 160, 0.2);
    }

    .booking-summary p {
        color: var(--text-primary);
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .booking-summary strong {
        color: var(--text-primary);
    }

    .total-revenue {
        color: var(--accent-green);
        font-weight: 700;
    }

    /* Responsive */
    @media (max-width: 380px) {
        .stat-slide {
            width: calc(100% - 24px);
            max-width: 240px;
        }
        
        .stat-card {
            padding: 20px;
            height: 140px;
        }
        
        .stat-icon {
            font-size: 2rem;
        }
        
        .stat-card .number {
            font-size: 2rem;
        }
        
        .featured-movie {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .featured-movie-poster {
            width: 100px;
            height: 140px;
        }
        
        .bottom-nav {
            padding: 4px 6px;
        }
        
        .nav-item {
            font-size: 0.55rem;
            padding: 4px 6px;
        }
        
        .nav-item i {
            font-size: 1rem;
        }
        
        .movie-actions {
            justify-content: center;
        }
    }

/* Style for select dropdown options */
.form-group select option {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
    padding: 12px;
}

/* Style for select dropdown when open */
.form-group select:focus option {
    background-color: var(--bg-tertiary);
}

/* For Firefox */
.form-group select option:hover,
.form-group select option:checked {
    background: var(--accent-gradient);
    color: white;
}

/* For Chrome/Safari */
.form-group select::-webkit-listbox {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.form-group select option:checked {
    background: var(--accent-gradient);
    color: white;
}
</style>
</head>
<body>

<!-- Login Screen -->
<div id="loginScreen" class="login-screen" <?php echo isset($_SESSION['user_id']) && $_SESSION['username'] === 'admin' ? 'style="display:none;"' : ''; ?>>
    <div class="login-card">
        <div class="login-icon">
            <i class="fas fa-film"></i>
        </div>
        <h2>CineBook Admin</h2>
        <p>Sign in to manage your cinema</p>

        <div id="loginError" style="display: none; background: rgba(255, 140, 66, 0.1); color: var(--accent-primary); padding: 12px; border-radius: 16px; margin-bottom: 20px; font-size: 0.85rem; border: 1px solid rgba(255, 140, 66, 0.3);"></div>

        <form id="loginForm" style="text-align: left;">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Username</label>
                <input type="text" id="username" placeholder="Enter username" required value="admin">
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-lock"></i> Password</label>
                <input type="password" id="password" placeholder="Enter password" required value="admin123">
            </div>

            <button type="button" class="btn-primary" onclick="handleLogin()">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        <p style="margin-top: 20px; font-size: 0.75rem; color: var(--text-secondary);">
            Default: admin / admin123
        </p>
    </div>
</div>

<!-- Mobile App Container -->
<div id="appContainer" class="app-container" <?php echo isset($_SESSION['user_id']) && $_SESSION['username'] === 'admin' ? 'style="display:block;"' : 'style="display:none;"'; ?>>
    <!-- Header -->
    <div class="app-header" id="appHeader">
        <div class="header-top">
            <h1>
                <i class="fas fa-film"></i> CineBook
            </h1>
            <div class="logout-btn" onclick="logout()">
                <i class="fas fa-sign-out-alt"></i> Logout
            </div>
        </div>
        
        <!-- Welcome Section -->
        <div class="welcome-section" id="welcomeSection">
            <div class="welcome-title">Welcome back, Admin!</div>
            <div class="welcome-subtitle">
                <span>Manage your cinema</span>
                <span class="date-badge">
                    <i class="fas fa-calendar-alt"></i> 
                    <span id="currentDate"></span>
                </span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- HOME SCREEN -->
        <div id="homeScreen" class="screen active">

            <!-- Stats Slider/Carousel -->
            <div class="stats-slider-container">
                <button class="slider-nav prev" onclick="slideStats('prev')">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="slider-nav next" onclick="slideStats('next')">
                    <i class="fas fa-chevron-right"></i>
                </button>
                
                <div class="stats-slider" id="statsSlider">
                    <div class="stat-slide">
                        <div class="stat-card" onclick="switchToScreen('usersScreen', document.querySelectorAll('.nav-item')[4])">
                            <div class="stat-icon"><i class="fas fa-users"></i></div>
                            <div class="number" id="totalUsers">0</div>
                            <div class="label">Total Users</div>
                        </div>
                    </div>
                    <div class="stat-slide">
                        <div class="stat-card" onclick="switchToScreen('bookingsScreen', document.querySelectorAll('.nav-item')[3])">
                            <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                            <div class="number" id="totalBookings">0</div>
                            <div class="label">Bookings</div>
                        </div>
                    </div>
                    <div class="stat-slide">
                        <div class="stat-card" onclick="switchToScreen('bookingsScreen', document.querySelectorAll('.nav-item')[3])">
                            <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                            <div class="number" id="totalRevenue">₱0</div>
                            <div class="label">Revenue</div>
                        </div>
                    </div>
                    <div class="stat-slide">
                        <div class="stat-card" onclick="switchToScreen('moviesScreen', document.querySelectorAll('.nav-item')[1])">
                            <div class="stat-icon"><i class="fas fa-film"></i></div>
                            <div class="number" id="totalMovies">0</div>
                            <div class="label">Movies</div>
                        </div>
                    </div>
                </div>
                
                <div class="slider-indicators" id="sliderIndicators">
                    <span class="indicator active" onclick="goToSlide(0)"></span>
                    <span class="indicator" onclick="goToSlide(1)"></span>
                    <span class="indicator" onclick="goToSlide(2)"></span>
                    <span class="indicator" onclick="goToSlide(3)"></span>
                </div>
            </div>

            <!-- Featured Section -->
            <div class="featured-section">
                <div class="section-title">
                    <h2><i class="fas fa-star"></i> Featured Movie</h2>
                    <span class="view-all" onclick="switchToScreen('moviesScreen', document.querySelectorAll('.nav-item')[1])">View All <i class="fas fa-arrow-right"></i></span>
                </div>
                <div class="featured-movie" id="featuredMovie">
                    <!-- Dynamic content will be inserted here -->
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="section-title">
                <h2><i class="fas fa-clock"></i> Recent Activity</h2>
                <span class="view-all" onclick="switchToScreen('bookingsScreen', document.querySelectorAll('.nav-item')[3])">View All <i class="fas fa-arrow-right"></i></span>
            </div>
            <div class="activity-list" id="recentActivity">
                <!-- Dynamic content will be inserted here -->
            </div>
        </div>

        <!-- MOVIES SCREEN -->
        <div id="moviesScreen" class="screen">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-film"></i> Movies</h3>
                    <span class="badge" id="moviesBadge">0</span>
                </div>

                <!-- Toggle between Now Showing and Coming Soon -->
                <div class="movie-toggle">
                    <div class="toggle-btn active" id="btnNowShowing" onclick="showMovieSection('now')">Now Showing</div>
                    <div class="toggle-btn" id="btnComingSoon" onclick="showMovieSection('coming')">Coming Soon</div>
                </div>

                <!-- Now Showing Section -->
                <div id="nowShowingSection" class="movie-section active">
                    <!-- Edit Movie Form (Now Showing) -->
                    <div id="editMovieForm" class="edit-form-container" style="display: none;">
                        <div class="edit-form-header">
                            <h4><i class="fas fa-edit"></i> Edit Movie</h4>
                            <span class="cancel-edit" onclick="cancelEdit()"><i class="fas fa-times"></i> Cancel</span>
                        </div>
                        <form class="mobile-form" onsubmit="updateMovie(event)">
                            <input type="hidden" id="editMovieId">
                            
                            <div class="form-group">
                                <label><i class="fas fa-heading"></i> Title</label>
                                <input type="text" id="editTitle" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-align-left"></i> Description</label>
                                <textarea id="editDescription" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-tags"></i> Genre</label>
                                <input type="text" id="editGenre">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-clock"></i> Duration</label>
                                <input type="text" id="editDuration" placeholder="e.g., 2h 30m">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> Price</label>
                                <input type="number" step="0.01" id="editPrice">
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-users"></i> Cast</label>
                                <textarea id="editCast" rows="1" placeholder="Separate with commas"></textarea>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-film"></i> Rating</label>
                                <select id="editRating">
                                    <option value="">Select</option>
                                    <option value="G">G</option>
                                    <option value="PG">PG</option>
                                    <option value="PG-13">PG-13</option>
                                    <option value="R">R</option>
                                    <option value="NC-17">NC-17</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label><i class="fas fa-calendar"></i> Release Date</label>
                                <input type="date" id="editReleaseDate">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fab fa-youtube"></i> YouTube Trailer ID</label>
                                <input type="text" id="editTrailerUrl" placeholder="e.g., dQw4w9WgXcQ">
                            </div>
                            
                            <div class="form-group">
                                <label><i class="fas fa-image"></i> Poster Image</label>
                                <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                                    <input type="file" id="editMoviePosterFile" accept="image/*" style="display: none;">
                                    <button type="button" class="btn-secondary" onclick="document.getElementById('editMoviePosterFile').click()" style="flex: 1; padding: 10px;">
                                        <i class="fas fa-upload"></i> Change Image
                                    </button>
                                    <span id="editMoviePosterFileName" style="color: var(--text-secondary); font-size: 0.8rem;">No file chosen</span>
                                </div>
                                <input type="hidden" id="editPoster" name="poster">
                                <div id="editMoviePosterPreview" style="margin-top: 8px;">
                                    <img src="" alt="Current Poster" style="max-width: 80px; max-height: 80px; border-radius: 8px; display: none;">
                                </div>
                            </div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Update Movie
                            </button>
                        </form>
                    </div>

                    <!-- Add Movie Form (Now Showing) -->
                    <details>
                        <summary><i class="fas fa-plus-circle"></i> Add New Movie</summary>
                        <div style="margin-top: 12px;">
                            <form class="mobile-form" onsubmit="addMovie(event)">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" id="movieTitle" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="movieDescription" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Genre</label>
                                    <input type="text" id="movieGenre">
                                </div>
                                <div class="form-group">
                                    <label>Duration</label>
                                    <input type="text" id="movieDuration" placeholder="e.g., 2h 15m">
                                </div>
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="number" step="0.01" id="moviePrice">
                                </div>
                                <div class="form-group">
                                    <label>Cast</label>
                                    <textarea id="movieCast" rows="1" placeholder="Separate with commas"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Rating</label>
                                    <select id="movieRating">
                                        <option value="">Select</option>
                                        <option value="G">G</option>
                                        <option value="PG">PG</option>
                                        <option value="PG-13">PG-13</option>
                                        <option value="R">R</option>
                                        <option value="NC-17">NC-17</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Release Date</label>
                                    <input type="date" id="movieReleaseDate">
                                </div>
                                <div class="form-group">
                                    <label><i class="fab fa-youtube"></i> YouTube Trailer ID</label>
                                    <input type="text" id="movieTrailerUrl" placeholder="e.g., dQw4w9WgXcQ">
                                </div>
                                <div class="form-group">
                                    <label>Poster Image</label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="file" id="moviePosterFile" accept="image/*" style="display: none;">
                                        <button type="button" class="btn-secondary" onclick="document.getElementById('moviePosterFile').click()" style="flex: 1; padding: 10px;">
                                            <i class="fas fa-upload"></i> Choose Image
                                        </button>
                                        <span id="moviePosterFileName" style="color: var(--text-secondary); font-size: 0.8rem;">No file chosen</span>
                                    </div>
                                    <input type="hidden" id="moviePoster" name="poster">
                                    <div id="moviePosterPreview" style="margin-top: 8px; display: none;">
                                        <img src="" alt="Preview" style="max-width: 80px; max-height: 80px; border-radius: 8px;">
                                    </div>
                                </div>
                                <input type="hidden" id="movieIsComingSoon" value="0">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-plus"></i> Add Movie
                                </button>
                            </form>
                        </div>
                    </details>

                    <!-- Now Showing List -->
                    <div class="movie-grid" id="moviesList"></div>
                </div>

                <!-- Coming Soon Section -->
                <div id="comingSoonSection" class="movie-section">
                    <!-- Edit Coming Soon Form -->
                    <div id="editCsMovieForm" class="edit-form-container" style="display: none;">
                        <div class="edit-form-header">
                            <h4><i class="fas fa-edit"></i> Edit Coming Soon</h4>
                            <span class="cancel-edit" onclick="cancelCsEdit()"><i class="fas fa-times"></i> Cancel</span>
                        </div>
                        <form class="mobile-form" onsubmit="updateComingSoon(event)">
                            <input type="hidden" id="editCsMovieId">
                            
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" id="editCsTitle" required>
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="editCsDescription" rows="2"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Genre</label>
                                <input type="text" id="editCsGenre">
                            </div>

                            <div class="form-group">
                                <label>Duration</label>
                                <input type="text" id="editCsDuration">
                            </div>

                            <div class="form-group">
                                <label>Cast</label>
                                <textarea id="editCsCast" rows="1"></textarea>
                            </div>

                            <div class="form-group">
                                <label>Rating</label>
                                <select id="editCsRating">
                                    <option value="">Select</option>
                                    <option value="G">G</option>
                                    <option value="PG">PG</option>
                                    <option value="PG-13">PG-13</option>
                                    <option value="R">R</option>
                                    <option value="NC-17">NC-17</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Release Date</label>
                                <input type="date" id="editCsReleaseDate" required>
                            </div>

                            <div class="form-group">
                                <label><i class="fab fa-youtube"></i> YouTube Trailer ID</label>
                                <input type="text" id="editCsTrailerUrl" placeholder="e.g., dQw4w9WgXcQ">
                            </div>

                            <div class="form-group">
                                <label>Poster Image</label>
                                <div style="display: flex; gap: 8px; align-items: center;">
                                    <input type="file" id="editCsPosterFile" accept="image/*" style="display: none;">
                                    <button type="button" class="btn-secondary" onclick="document.getElementById('editCsPosterFile').click()" style="flex: 1; padding: 10px;">
                                        <i class="fas fa-upload"></i> Choose Image
                                    </button>
                                    <span id="editCsPosterFileName" style="color: var(--text-secondary); font-size: 0.8rem;">No file chosen</span>
                                </div>
                                <input type="hidden" id="editCsPoster" name="poster">
                                <div id="editCsPosterPreview" style="margin-top: 8px; display: none;">
                                    <img src="" alt="Preview" style="max-width: 80px; max-height: 80px; border-radius: 8px;">
                                </div>
                            </div>

                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save"></i> Update
                            </button>
                        </form>
                    </div>

                    <details>
                        <summary><i class="fas fa-plus-circle"></i> Add Coming Soon</summary>
                        <div style="margin-top: 12px;">
                            <form class="mobile-form" onsubmit="addComingSoon(event)">
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" id="csTitle" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea id="csDescription" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Genre</label>
                                    <input type="text" id="csGenre">
                                </div>
                                <div class="form-group">
                                    <label>Duration</label>
                                    <input type="text" id="csDuration">
                                </div>
                                <div class="form-group">
                                    <label>Cast</label>
                                    <textarea id="csCast" rows="1"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Rating</label>
                                    <select id="csRating">
                                        <option value="">Select</option>
                                        <option value="G">G</option>
                                        <option value="PG">PG</option>
                                        <option value="PG-13">PG-13</option>
                                        <option value="R">R</option>
                                        <option value="NC-17">NC-17</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Release Date</label>
                                    <input type="date" id="csReleaseDate" required>
                                </div>
                                <div class="form-group">
                                    <label><i class="fab fa-youtube"></i> YouTube Trailer ID</label>
                                    <input type="text" id="csTrailerUrl" placeholder="e.g., dQw4w9WgXcQ">
                                </div>
                                <div class="form-group">
                                    <label>Poster Image</label>
                                    <div style="display: flex; gap: 8px; align-items: center;">
                                        <input type="file" id="csPosterFile" accept="image/*" style="display: none;">
                                        <button type="button" class="btn-secondary" onclick="document.getElementById('csPosterFile').click()" style="flex: 1; padding: 10px;">
                                            <i class="fas fa-upload"></i> Choose Image
                                        </button>
                                        <span id="csPosterFileName" style="color: var(--text-secondary); font-size: 0.8rem;">No file chosen</span>
                                    </div>
                                    <input type="hidden" id="csPoster" name="poster">
                                    <div id="csPosterPreview" style="margin-top: 8px; display: none;">
                                        <img src="" alt="Preview" style="max-width: 80px; max-height: 80px; border-radius: 8px;">
                                    </div>
                                </div>
                                <input type="hidden" id="csIsComingSoon" value="1">
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </form>
                        </div>
                    </details>

                    <div class="movie-grid" id="comingSoonList"></div>
                </div>
            </div>
        </div>

        <!-- SEATS SCREEN -->
        <div id="seatsScreen" class="screen">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-chair"></i> Manage Seats</h3>
                </div>

                <div class="filter-bar">
                    <div class="filter-group">
                        <label><i class="fas fa-film"></i> Select Movie</label>
                        <select id="mobileMovieSelect">
                            <option value="">-- Choose Movie --</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label><i class="fas fa-calendar"></i> Date</label>
                        <input type="date" id="mobileShowDate">
                    </div>

                    <div class="filter-group">
                        <label><i class="fas fa-clock"></i> Time</label>
                        <select id="mobileShowTime">
                            <option value="10:00 AM">10:00 AM</option>
                            <option value="1:00 PM">1:00 PM</option>
                            <option value="4:00 PM">4:00 PM</option>
                            <option value="7:00 PM" selected>7:00 PM</option>
                            <option value="10:00 PM">10:00 PM</option>
                        </select>
                    </div>

                    <button class="btn-primary" onclick="loadMobileSeats()">
                        <i class="fas fa-chair"></i> Load Seats
                    </button>
                </div>

                <div id="mobileSeatControls" style="display: none;">
                    <div style="display: flex; gap: 8px; margin: 16px 0;">
                        <button class="btn-primary" style="flex: 1;" onclick="saveMobileSeats()">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button class="btn-secondary" style="flex: 1;" onclick="resetMobileSeats()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>

                <!-- Zoom Controls -->
                <div class="zoom-controls" id="zoomControls">
                    <button class="zoom-btn" id="zoomOut" onclick="zoomOut()">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span class="zoom-level" id="zoomLevel">100%</span>
                    <button class="zoom-btn" id="zoomIn" onclick="zoomIn()">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <div class="seat-container" id="seatContainer">
                    <div class="zoom-wrapper" id="zoomWrapper">
                        <div id="mobileSeatGrid" class="seat-grid"></div>
                        <!-- Screen Display -->
                        <div class="screen-display">SCREEN</div>
                    </div>
                </div>

                <div style="display: flex; gap: 16px; justify-content: center; margin-top: 16px; flex-wrap: wrap;">
                    <div class="legend-item"><span class="seat available"></span> Available</div>
                    <div class="legend-item"><span class="seat booked"></span> Booked</div>
                    <div class="legend-item"><span class="seat blocked"></span> Blocked</div>
                </div>
            </div>
        </div>

        <!-- BOOKINGS SCREEN -->
        <div id="bookingsScreen" class="screen">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-history"></i> Bookings</h3>
                </div>

                <div class="filter-bar">
                    <div class="filter-group">
                        <label>User</label>
                        <select id="userFilter">
                            <option value="">All Users</option>
                        </select>
                    </div>

                    <div class="filter-group">
                        <label>Movie</label>
                        <select id="movieFilter">
                            <option value="">All Movies</option>
                        </select>
                    </div>

                    <button class="btn-primary" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>

                    <button class="btn-secondary" onclick="clearFilters()" style="margin-top: 8px;">
                        <i class="fas fa-times"></i> Clear Filters
                    </button>
                </div>

                <div id="noBookingsMessage" class="empty-state" style="display: none;">
                    <i class="fas fa-inbox"></i>
                    <p>No bookings found</p>
                </div>

                <div style="overflow-x: auto;" id="bookingsTableContainer">
                    <table class="data-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Movie</th>
                                <th>Seats</th>
                                <th>Total</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="bookingsBody"></tbody>
                    </table>
                </div>

                <div class="booking-summary" id="bookingSummary" style="display: none;">
                    <p><strong>Total Revenue:</strong> <span class="total-revenue" id="totalRevenueDisplay">₱0.00</span></p>
                    <p><strong>Total Bookings:</strong> <span id="totalBookingsDisplay">0</span></p>
                </div>
            </div>
        </div>

        <!-- USERS SCREEN -->
        <div id="usersScreen" class="screen">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-users"></i> Users</h3>
                    <span class="badge" id="usersBadge">0</span>
                </div>

                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Points</th>
                                <th>Total Earned</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="usersBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- PROMOS SCREEN -->
        <div id="promosScreen" class="screen">
            <div class="section-card">
                <div class="section-header">
                    <h3><i class="fas fa-gift"></i> Promos</h3>
                    <span class="badge" id="promosBadge">0</span>
                </div>

                <!-- Edit Promo Form -->
                <div id="editPromoForm" class="edit-form-container" style="display: none;">
                    <div class="edit-form-header">
                        <h4><i class="fas fa-edit"></i> Edit Promo</h4>
                        <span class="cancel-edit" onclick="cancelPromoEdit()"><i class="fas fa-times"></i> Cancel</span>
                    </div>
                    <form class="mobile-form" onsubmit="updatePromo(event)">
                        <input type="hidden" id="editPromoId">
                        
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" id="editPromoTitle" required>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea id="editPromoDescription" rows="2"></textarea>
                        </div>

                        <div class="form-group">
                            <label>Points Required</label>
                            <input type="number" id="editPromoPoints" required>
                        </div>

                        <div class="form-group">
                            <label>Discount Amount (₱)</label>
                            <input type="number" step="0.01" id="editPromoAmount" placeholder="Leave empty for percentage">
                        </div>

                        <div class="form-group">
                            <label>Discount Percentage (%)</label>
                            <input type="number" step="0.01" id="editPromoPercentage" placeholder="Leave empty for fixed amount">
                        </div>

                        <div class="form-group">
                            <label>Icon Class</label>
                            <input type="text" id="editPromoIcon" placeholder="fa-gift, fa-ticket, etc.">
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" id="editPromoActive" checked>
                                Active
                            </label>
                        </div>

                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Update Promo
                        </button>
                    </form>
                </div>

                <details>
                    <summary><i class="fas fa-plus-circle"></i> Add New Promo</summary>
                    <div style="margin-top: 12px;">
                        <form class="mobile-form" onsubmit="addPromo(event)">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" id="promoTitle" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea id="promoDescription" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Points Required</label>
                                <input type="number" id="promoPoints" required>
                            </div>
                            <div class="form-group">
                                <label>Discount Amount (₱)</label>
                                <input type="number" step="0.01" id="promoAmount" placeholder="Leave empty for percentage">
                            </div>
                            <div class="form-group">
                                <label>Discount Percentage (%)</label>
                                <input type="number" step="0.01" id="promoPercentage" placeholder="Leave empty for fixed amount">
                            </div>
                            <div class="form-group">
                                <label>Icon Class</label>
                                <input type="text" id="promoIcon" placeholder="fa-gift, fa-ticket, etc." value="fa-gift">
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" id="promoActive" checked>
                                    Active
                                </label>
                            </div>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-plus"></i> Add Promo
                            </button>
                        </form>
                    </div>
                </details>

                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Points</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="promosBody"></tbody>
                    </table>
                </div>

                <h4 style="margin: 20px 0 12px; color: var(--accent-primary); font-size: 0.95rem;">Redeemed Promos</h4>
                <div style="overflow-x: auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Promo</th>
                                <th>Points</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="redeemedPromosBody"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <div class="nav-item active" onclick="switchToScreen('homeScreen', this)">
            <i class="fas fa-home"></i>
            <span>Home</span>
        </div>
        <div class="nav-item" onclick="switchToScreen('moviesScreen', this)">
            <i class="fas fa-film"></i>
            <span>Movies</span>
        </div>
        <div class="nav-item" onclick="switchToScreen('seatsScreen', this)">
            <i class="fas fa-chair"></i>
            <span>Seats</span>
        </div>
        <div class="nav-item" onclick="switchToScreen('bookingsScreen', this)">
            <i class="fas fa-ticket"></i>
            <span>Bookings</span>
        </div>
        <div class="nav-item" onclick="switchToScreen('usersScreen', this)">
            <i class="fas fa-users"></i>
            <span>Users</span>
        </div>
        <div class="nav-item" onclick="switchToScreen('promosScreen', this)">
            <i class="fas fa-gift"></i>
            <span>Promos</span>
        </div>
    </div>
</div>

<!-- User Details Modal -->
<div class="modal" id="userModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user"></i> <span id="modalUsername"></span></h3>
            <button class="close-modal" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div style="margin-bottom: 20px;">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div style="background: rgba(255, 140, 66, 0.1); padding: 12px; border-radius: 16px; text-align: center; border: 1px solid rgba(255, 140, 66, 0.3);">
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--accent-primary);" id="modalPoints">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Current Points</div>
                    </div>
                    <div style="background: rgba(6, 214, 160, 0.1); padding: 12px; border-radius: 16px; text-align: center; border: 1px solid rgba(6, 214, 160, 0.3);">
                        <div style="font-size: 1.5rem; font-weight: 800; color: var(--accent-green);" id="modalTotalPoints">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary);">Total Earned</div>
                    </div>
                </div>
            </div>

            <h4 style="margin-bottom: 10px; color: var(--text-primary); font-size: 0.9rem;">Recent Bookings</h4>
            <div id="modalBookings"></div>

            <h4 style="margin: 16px 0 10px; color: var(--text-primary); font-size: 0.9rem;">Redeemed Promos</h4>
            <div id="modalPromos"></div>
        </div>
    </div>
</div>

<!-- Trailer Modal -->
<div class="modal" id="trailerModal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3><i class="fab fa-youtube"></i> <span id="trailerModalTitle"></span></h3>
            <button class="close-modal" onclick="closeTrailerModal()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 0;">
            <div class="trailer-container" id="trailerContainer">
                <iframe id="trailerIframe" src="" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage"></span>
</div>

<script>
// ==================== GLOBAL VARIABLES ====================
let currentUser = <?php echo isset($_SESSION['user_id']) && $_SESSION['username'] === 'admin' ? '{username: "admin"}' : 'null'; ?>;
let currentScreen = 'homeScreen';
let seatConfig = {};
let occupiedSeats = [];

// Data arrays (will be populated from database)
let movies = [];
let comingSoon = [];
let users = [];
let bookings = [];
let promos = [];
let redeemedPromos = [];

// Zoom variables
let currentZoom = 1;
const ZOOM_STEP = 0.2;
const MIN_ZOOM = 0.5;
const MAX_ZOOM = 3;

let isSubmitting = false; // Flag to prevent multiple submissions

// ==================== ZOOM FUNCTIONS ====================
function setZoom(zoom) {
    currentZoom = zoom;
    $('.zoom-wrapper').css('transform', `scale(${currentZoom})`);
    $('#zoomLevel').text(Math.round(currentZoom * 100) + '%');
}

function zoomIn() {
    let newZoom = Math.min(currentZoom + ZOOM_STEP, MAX_ZOOM);
    setZoom(newZoom);
}

function zoomOut() {
    let newZoom = Math.max(currentZoom - ZOOM_STEP, MIN_ZOOM);
    setZoom(newZoom);
}

function resetZoom() {
    setZoom(1);
}

// ==================== PINCH ZOOM SUPPORT ====================
function initPinchZoom() {
    const container = document.getElementById('seatContainer');
    if (!container) return;
    
    let initialDistance = 0;
    let initialZoom = currentZoom;
    
    container.addEventListener('touchstart', function(e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            const dx = e.touches[0].clientX - e.touches[1].clientX;
            const dy = e.touches[0].clientY - e.touches[1].clientY;
            initialDistance = Math.sqrt(dx * dx + dy * dy);
            initialZoom = currentZoom;
        }
    }, { passive: false });
    
    container.addEventListener('touchmove', function(e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            const dx = e.touches[0].clientX - e.touches[1].clientX;
            const dy = e.touches[0].clientY - e.touches[1].clientY;
            const currentDistance = Math.sqrt(dx * dx + dy * dy);
            
            const zoomDelta = (currentDistance - initialDistance) / 200;
            let newZoom = initialZoom + zoomDelta;
            
            // Clamp zoom level
            newZoom = Math.max(MIN_ZOOM, Math.min(MAX_ZOOM, newZoom));
            
            setZoom(newZoom);
        }
    }, { passive: false });
    
    container.addEventListener('touchend', function(e) {
        if (e.touches.length < 2) {
            initialDistance = 0;
        }
    });
}

// ==================== SCREEN NAVIGATION ====================
function switchToScreen(screenId, element) {
    // Hide all screens
    const screens = document.querySelectorAll('.screen');
    screens.forEach(screen => {
        screen.classList.remove('active');
    });
    
    // Show selected screen
    const selectedScreen = document.getElementById(screenId);
    if (selectedScreen) {
        selectedScreen.classList.add('active');
    }
    
    // Update nav items
    document.querySelectorAll('.nav-item').forEach(item => {
        item.classList.remove('active');
    });
    if (element) {
        element.classList.add('active');
    }
    
    currentScreen = screenId;
    
    // Show/hide app header based on screen
    const appHeader = document.getElementById('appHeader');
    const welcomeSection = document.getElementById('welcomeSection');
    
    if (screenId === 'homeScreen') {
        appHeader.style.display = 'block';
        welcomeSection.style.display = 'block';
    } else {
        appHeader.style.display = 'none';
    }
    
    // Initialize specific screens if needed
    if (screenId === 'seatsScreen') {
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('mobileShowDate');
        if (dateInput && !dateInput.value) {
            dateInput.value = today;
        }
        populateMovieSelect();
        showToast('Select a movie and date to manage seats');
        
        // Initialize pinch zoom when seats screen is shown
        setTimeout(() => {
            initPinchZoom();
            resetZoom();
        }, 100);
    } else if (screenId === 'homeScreen') {
        updateHomeScreen();
    } else if (screenId === 'moviesScreen') {
        // Reset to Now Showing view
        showMovieSection('now');
    }
}

// ==================== MOVIE SECTION TOGGLE ====================
function showMovieSection(section) {
    const nowBtn = document.getElementById('btnNowShowing');
    const comingBtn = document.getElementById('btnComingSoon');
    const nowSection = document.getElementById('nowShowingSection');
    const comingSection = document.getElementById('comingSoonSection');
    
    if (section === 'now') {
        nowBtn.classList.add('active');
        comingBtn.classList.remove('active');
        nowSection.classList.add('active');
        comingSection.classList.remove('active');
        renderMovies(); // Refresh now showing list
    } else {
        comingBtn.classList.add('active');
        nowBtn.classList.remove('active');
        comingSection.classList.add('active');
        nowSection.classList.remove('active');
        renderComingSoon(); // Refresh coming soon list
    }
}

// ==================== SLIDER FUNCTIONS ====================
function slideStats(direction) {
    const slider = document.getElementById('statsSlider');
    const slideWidth = slider.querySelector('.stat-slide').offsetWidth + 16;
    const currentScroll = slider.scrollLeft;
    
    if (direction === 'next') {
        slider.scrollTo({
            left: currentScroll + slideWidth,
            behavior: 'smooth'
        });
    } else {
        slider.scrollTo({
            left: currentScroll - slideWidth,
            behavior: 'smooth'
        });
    }
}

function goToSlide(index) {
    const slider = document.getElementById('statsSlider');
    const slideWidth = slider.querySelector('.stat-slide').offsetWidth + 16;
    slider.scrollTo({
        left: slideWidth * index,
        behavior: 'smooth'
    });
}

function updateSliderIndicators() {
    const slider = document.getElementById('statsSlider');
    const indicators = document.querySelectorAll('.indicator');
    const slideWidth = slider.querySelector('.stat-slide').offsetWidth + 16;
    const currentIndex = Math.round(slider.scrollLeft / slideWidth);
    
    indicators.forEach((indicator, index) => {
        if (index === currentIndex) {
            indicator.classList.add('active');
        } else {
            indicator.classList.remove('active');
        }
    });
    
    const prevBtn = document.querySelector('.slider-nav.prev');
    const nextBtn = document.querySelector('.slider-nav.next');
    
    if (currentIndex === 0) {
        prevBtn.style.display = 'none';
    } else {
        prevBtn.style.display = 'flex';
    }
    
    if (currentIndex === indicators.length - 1) {
        nextBtn.style.display = 'none';
    } else {
        nextBtn.style.display = 'flex';
    }
}

// ==================== LOGIN FUNCTIONS ====================
function handleLogin() {
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    
    $.ajax({
        url: 'api.php?action=login',
        type: 'POST',
        data: JSON.stringify({
            username: username,
            password: password
        }),
        contentType: 'application/json',
        success: function(response) {
            if (response.success && response.username === 'admin') {
                currentUser = { username: 'admin' };
                document.getElementById('loginScreen').style.display = 'none';
                document.getElementById('appContainer').style.display = 'block';
                showToast('Welcome back, Admin!');
                loadDashboardData();
                updateHomeScreen();
                
                const slider = document.getElementById('statsSlider');
                slider.addEventListener('scroll', updateSliderIndicators);
                
                // Switch to home screen
                switchToScreen('homeScreen', document.querySelector('.nav-item'));
            } else {
                const errorDiv = document.getElementById('loginError');
                errorDiv.style.display = 'block';
                errorDiv.textContent = 'Invalid admin credentials';
            }
        },
        error: function() {
            const errorDiv = document.getElementById('loginError');
            errorDiv.style.display = 'block';
            errorDiv.textContent = 'Login failed. Please try again.';
        }
    });
}

function logout() {
    $.ajax({
        url: 'api.php?action=logout',
        type: 'POST',
        success: function() {
            currentUser = null;
            document.getElementById('loginScreen').style.display = 'flex';
            document.getElementById('appContainer').style.display = 'none';
            showToast('Logged out successfully');
        }
    });
}

// ==================== DASHBOARD FUNCTIONS ====================
function loadDashboardData() {
    $.getJSON('api.php?action=admin_get_all', function(data) {
        if (data) {
            // Clear existing arrays
            movies = [];
            comingSoon = [];
            
            // Separate movies into now showing and coming soon
            if (data.movies && Array.isArray(data.movies)) {
                data.movies.forEach(function(movie) {
                    // Check if is_coming_soon is 1, "1", or true
                    const isComingSoon = 
                        movie.is_coming_soon === 1 || 
                        movie.is_coming_soon === "1" || 
                        movie.is_coming_soon === true;
                    
                    if (isComingSoon) {
                        comingSoon.push(movie);
                    } else {
                        movies.push(movie);
                    }
                });
            }
            
            if (data.users) {
                users = data.users;
            }
            
            if (data.bookings) {
                bookings = data.bookings;
                console.log('Bookings loaded:', bookings); // Debug log
                
                // Enrich bookings with username data
                enrichBookingsWithUserData();
            }
            
            if (data.promos) {
                promos = data.promos;
            }
            
            if (data.redeemed) {
                redeemedPromos = data.redeemed;
            }
            
            // Update all displays
            updateStats();
            renderMovies();
            renderComingSoon();
            renderUsers();
            renderBookings();
            renderPromos();
            renderRedeemedPromos();
            populateMovieSelect();
            populateFilters();
            updateHomeScreen();
        }
    }).fail(function(jqXHR, textStatus, errorThrown) {
        console.error('Error loading dashboard data:', {
            status: textStatus,
            error: errorThrown,
            response: jqXHR.responseText
        });
        showToast('Error loading dashboard data');
    });
}

// New function to enrich bookings with username data
function enrichBookingsWithUserData() {
    // Create a map of user_id to username for quick lookup
    const userMap = {};
    users.forEach(user => {
        userMap[user.id] = user.username;
    });
    
    // Add username to each booking
    bookings = bookings.map(booking => {
        return {
            ...booking,
            username: userMap[booking.user_id] || 'Unknown User'
        };
    });
    
    console.log('Enriched bookings:', bookings);
}

function updateStats() {
    // Filter out admin user for user count
    const regularUsers = users.filter(u => u.username !== 'admin');
    document.getElementById('totalUsers').textContent = regularUsers.length;
    document.getElementById('totalBookings').textContent = bookings.length;
    
    const totalRevenue = bookings.reduce((sum, b) => sum + (parseFloat(b.total) || 0), 0);
    document.getElementById('totalRevenue').textContent = '₱' + totalRevenue.toFixed(2);
    
    document.getElementById('totalMovies').textContent = movies.length + comingSoon.length;
    document.getElementById('moviesBadge').textContent = movies.length + comingSoon.length;
    document.getElementById('usersBadge').textContent = regularUsers.length;
    document.getElementById('promosBadge').textContent = promos.filter(p => p.is_active).length;
}

// ==================== HOME SCREEN FUNCTIONS ====================
function updateHomeScreen() {
    const today = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    document.getElementById('currentDate').textContent = today;
    
    if (movies.length > 0) {
        const featured = movies[0];
        const featuredEl = document.getElementById('featuredMovie');
        featuredEl.innerHTML = `
            <div class="featured-movie-poster">
                <i class="fas fa-film"></i>
            </div>
            <div class="featured-movie-info">
                <h3>${featured.title || 'Untitled'}</h3>
                <div class="featured-movie-meta">
                    <span><i class="fas fa-clock"></i> ${featured.duration || 'N/A'}</span>
                    <span><i class="fas fa-tag"></i> ${featured.genre || 'General'}</span>
                </div>
                <div class="featured-movie-rating">${featured.rating || 'PG'}</div>
                <div class="featured-movie-price">
                    ₱${parseFloat(featured.price || 0).toFixed(2)}
                    <small>/ ticket</small>
                </div>
            </div>
        `;
    } else {
        document.getElementById('featuredMovie').innerHTML = '<div class="empty-state">No movies available</div>';
    }
    
    const activityEl = document.getElementById('recentActivity');
    activityEl.innerHTML = '';
    
    const recentBookings = [...bookings].sort((a, b) => 
        new Date(b.booking_time || 0) - new Date(a.booking_time || 0)
    ).slice(0, 3);
    
    if (recentBookings.length > 0) {
        recentBookings.forEach(booking => {
            const item = document.createElement('div');
            item.className = 'activity-item';
            item.innerHTML = `
                <div class="activity-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="activity-details">
                    <div class="activity-title">${booking.username || 'User'} booked ${booking.movie || 'movie'}</div>
                    <div class="activity-time">
                        <i class="fas fa-clock"></i> ${booking.booking_time ? new Date(booking.booking_time).toLocaleDateString() : 'N/A'}
                    </div>
                </div>
                <div class="activity-amount">₱${parseFloat(booking.total || 0).toFixed(2)}</div>
            `;
            activityEl.appendChild(item);
        });
    } else {
        activityEl.innerHTML = '<div class="empty-state">No recent activity</div>';
    }
}

// ==================== MOVIE FUNCTIONS ====================
function renderMovies() {
    const list = document.getElementById('moviesList');
    list.innerHTML = '';
    
    // Filter to ensure we ONLY show movies with is_coming_soon = 0
    const nowShowingMovies = movies.filter(m => 
        m.is_coming_soon === 0 || 
        m.is_coming_soon === "0" || 
        m.is_coming_soon === false
    );
    
    if (nowShowingMovies.length === 0) {
        list.innerHTML = '<div class="empty-state">No movies available</div>';
        return;
    }
    
    nowShowingMovies.forEach(movie => {
        // Create image HTML with fallback
        let posterHtml = '<i class="fas fa-film"></i>';
        if (movie.poster) {
            const baseUrl = window.location.origin + '/mobile_app/';
            const posterUrl = movie.poster.startsWith('http') ? movie.poster : baseUrl + movie.poster;
            
            posterHtml = `<img src="${posterUrl}" alt="${movie.title}" 
                          onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2270%22%20height%3D%22100%22%20viewBox%3D%220%200%2070%20100%22%3E%3Crect%20width%3D%2270%22%20height%3D%22100%22%20fill%3D%22%232a2a3a%22%2F%3E%3Ctext%20x%3D%2235%22%20y%3D%2250%22%20font-size%3D%2220%22%20text-anchor%3D%22middle%22%20fill%3D%22%23ff8c42%22%20font-family%3D%22FontAwesome%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E');">`;
        }
        
        const card = document.createElement('div');
        card.className = 'movie-card';
        card.innerHTML = `
            <div class="movie-header">
                <div class="movie-poster">
                    ${posterHtml}
                </div>
                <div class="movie-info">
                    <div class="movie-title">${movie.title || 'Untitled'}</div>
                    <div class="movie-meta">
                        <span>${movie.genre || 'General'}</span>
                        <span>${movie.duration || 'N/A'}</span>
                        <span class="rating-badge">${movie.rating || 'PG'}</span>
                    </div>
<div class="movie-price">
    <i class="fas fa-ticket-alt"></i> ₱${parseFloat(movie.price || 0).toFixed(2)}
</div>
                </div>
            </div>
            <div class="movie-actions">
                <span class="action-btn edit" onclick="editMovie(${movie.id})">
                    <i class="fas fa-edit"></i> Edit
                </span>
                <span class="action-btn delete" onclick="deleteMovie(${movie.id})">
                    <i class="fas fa-trash"></i> Delete
                </span>
                <span class="action-btn move" onclick="moveToComingSoon(${movie.id})">
                    <i class="fas fa-clock"></i> Move
                </span>
                ${movie.trailer_url ? 
                    `<span class="action-btn trailer" onclick="playTrailer('${movie.title.replace(/'/g, "\\'")}', '${movie.trailer_url}')">
                        <i class="fab fa-youtube"></i> Trailer
                    </span>` : 
                    ''
                }
            </div>
        `;
        list.appendChild(card);
    });
}

function renderComingSoon() {
    const list = document.getElementById('comingSoonList');
    list.innerHTML = '';
    
    // Filter to ensure we ONLY show movies with is_coming_soon = 1
    const comingSoonMovies = comingSoon.filter(m => 
        m.is_coming_soon === 1 || 
        m.is_coming_soon === "1" || 
        m.is_coming_soon === true
    );
    
    if (comingSoonMovies.length === 0) {
        list.innerHTML = '<div class="empty-state">No coming soon movies</div>';
        return;
    }
    
    comingSoonMovies.forEach(movie => {
        const releaseDate = movie.release_date ? new Date(movie.release_date) : new Date();
        const formattedDate = releaseDate.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
        
        // Create image HTML with fallback
        let posterHtml = '<i class="fas fa-film"></i>';
        if (movie.poster) {
            const baseUrl = window.location.origin + '/mobile_app/';
            const posterUrl = movie.poster.startsWith('http') ? movie.poster : baseUrl + movie.poster;
            
            posterHtml = `<img src="${posterUrl}" alt="${movie.title}" 
                          onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%2270%22%20height%3D%22100%22%20viewBox%3D%220%200%2070%20100%22%3E%3Crect%20width%3D%2270%22%20height%3D%22100%22%20fill%3D%22%232a2a3a%22%2F%3E%3Ctext%20x%3D%2235%22%20y%3D%2250%22%20font-size%3D%2220%22%20text-anchor%3D%22middle%22%20fill%3D%22%23ff8c42%22%20font-family%3D%22FontAwesome%22%3E%3F%3C%2Ftext%3E%3C%2Fsvg%3E');">`;
        }
        
        const card = document.createElement('div');
        card.className = 'movie-card';
        
        // ADD THE COMING SOON BADGE HERE - right after creating the card
        const comingSoonBadge = document.createElement('div');
        comingSoonBadge.className = 'coming-soon-badge';
        comingSoonBadge.innerHTML = '<i class="fas fa-clock"></i> Coming Soon';
        card.appendChild(comingSoonBadge);
        
        // THEN set the innerHTML
        card.innerHTML += `
            <div class="movie-header">
                <div class="movie-poster">
                    ${posterHtml}
                </div>
                <div class="movie-info">
                    <div class="movie-title">${movie.title || 'Untitled'}</div>
                    <div class="movie-meta">
                        <span><i class="fas fa-tag"></i> ${movie.genre || 'General'}</span>
                        <span><i class="fas fa-clock"></i> ${movie.duration || 'N/A'}</span>
                        <span class="rating-badge">${movie.rating || 'PG'}</span>
                    </div>
                    <div class="release-date">
                        <i class="fas fa-calendar-alt"></i> Releases: ${formattedDate}
                    </div>
                </div>
            </div>
            <div class="movie-actions">
                <span class="action-btn edit" onclick="editComingSoon(${movie.id})">
                    <i class="fas fa-edit"></i> Edit
                </span>
                <span class="action-btn delete" onclick="deleteComingSoon(${movie.id})">
                    <i class="fas fa-trash"></i> Delete
                </span>
                <span class="action-btn move" onclick="moveToCurrent(${movie.id})">
                    <i class="fas fa-play"></i> Move
                </span>
                ${movie.trailer_url ? 
                    `<span class="action-btn trailer" onclick="playTrailer('${movie.title.replace(/'/g, "\\'")}', '${movie.trailer_url}')">
                        <i class="fab fa-youtube"></i> Trailer
                    </span>` : 
                    ''
                }
            </div>
        `;
        list.appendChild(card);
    });
}

function addMovie(event) {
    event.preventDefault();
    
    // Prevent multiple submissions
    if (isSubmitting) {
        showToast('Please wait...');
        return;
    }
    
    const title = document.getElementById('movieTitle').value;
    const description = document.getElementById('movieDescription').value;
    const genre = document.getElementById('movieGenre').value;
    const duration = document.getElementById('movieDuration').value;
    const price = parseFloat(document.getElementById('moviePrice').value) || 0;
    const cast = document.getElementById('movieCast').value;
    const rating = document.getElementById('movieRating').value;
    const release_date = document.getElementById('movieReleaseDate').value;
    const trailer_url = document.getElementById('movieTrailerUrl').value;
    const poster = document.getElementById('moviePoster').value;
    
    // Validate required fields
    if (!title) {
        showToast('Please enter a movie title');
        return;
    }
    
    const newMovie = {
        title: title,
        description: description,
        genre: genre,
        duration: duration,
        price: price,
        cast: cast,
        rating: rating,
        release_date: release_date,
        trailer_url: trailer_url,
        poster: poster,
        is_coming_soon: 0
    };
    
    // Set submitting flag
    isSubmitting = true;
    
    // Disable the submit button
    const submitBtn = event.target.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }
    
    $.ajax({
        url: 'api.php?action=admin_add_movie',
        type: 'POST',
        data: JSON.stringify({movie: newMovie}),
        contentType: 'application/json',
        success: function(response) {
            // Reset submitting flag
            isSubmitting = false;
            
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Movie';
            }
            
            if (response.success) {
                showToast('Movie added successfully!');
                
                // Reset form
                event.target.reset();
                
                // Reset file upload UI
                document.getElementById('moviePosterFileName').textContent = 'No file chosen';
                document.getElementById('moviePosterPreview').style.display = 'none';
                document.getElementById('moviePoster').value = '';
                
                // Close details
                const details = event.target.closest('details');
                if (details) details.open = false;
                
                // Reload data
                loadDashboardData();
            } else {
                showToast('Error adding movie: ' + (response.message || 'Unknown error'));
            }
        },
        error: function() {
            // Reset submitting flag
            isSubmitting = false;
            
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Movie';
            }
            
            showToast('Error adding movie');
        }
    });
}

function editMovie(id) {
    const movie = movies.find(m => m.id == id);
    if (!movie) return;
    
    document.getElementById('editMovieId').value = movie.id;
    document.getElementById('editTitle').value = movie.title || '';
    document.getElementById('editDescription').value = movie.description || '';
    document.getElementById('editGenre').value = movie.genre || '';
    document.getElementById('editDuration').value = movie.duration || '';
    document.getElementById('editPrice').value = movie.price || 0;
    document.getElementById('editCast').value = movie.cast || '';
    document.getElementById('editRating').value = movie.rating || '';
    document.getElementById('editReleaseDate').value = movie.release_date || '';
    document.getElementById('editTrailerUrl').value = movie.trailer_url || '';
    document.getElementById('editPoster').value = movie.poster || '';
    
    // Show current poster preview if exists
    if (movie.poster) {
        const preview = document.getElementById('editMoviePosterPreview');
        preview.style.display = 'block';
        preview.querySelector('img').src = movie.poster;
        preview.querySelector('img').style.display = 'block';
        document.getElementById('editMoviePosterFileName').textContent = 'Current: ' + movie.poster.split('/').pop();
    }
    
    document.getElementById('editMovieForm').style.display = 'block';
}

function updateMovie(event) {
    event.preventDefault();
    const id = parseInt(document.getElementById('editMovieId').value);
    
    const updatedMovie = {
        id: id,
        title: document.getElementById('editTitle').value,
        description: document.getElementById('editDescription').value,
        genre: document.getElementById('editGenre').value,
        duration: document.getElementById('editDuration').value,
        price: parseFloat(document.getElementById('editPrice').value) || 0,
        cast: document.getElementById('editCast').value,
        rating: document.getElementById('editRating').value,
        release_date: document.getElementById('editReleaseDate').value,
        trailer_url: document.getElementById('editTrailerUrl').value,
        poster: document.getElementById('editPoster').value,
        is_coming_soon: 0
    };
    
    $.ajax({
        url: 'api.php?action=admin_update_movie',
        type: 'POST',
        data: JSON.stringify({movie: updatedMovie}),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                showToast('Movie updated successfully!');
                loadDashboardData();
                cancelEdit();
            } else {
                showToast('Error updating movie');
            }
        }
    });
}

function deleteMovie(id) {
    if (confirm('Delete this movie?')) {
        $.ajax({
            url: 'api.php?action=admin_delete_movie&id=' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    showToast('Movie deleted successfully!');
                    loadDashboardData();
                } else {
                    showToast('Error deleting movie');
                }
            }
        });
    }
}

function moveToComingSoon(id) {
    if (confirm('Move this movie to Coming Soon?')) {
        const movie = movies.find(m => m.id == id);
        if (movie) {
            const updatedMovie = {
                id: id,
                is_coming_soon: 1
            };
            
            $.ajax({
                url: 'api.php?action=admin_update_movie',
                type: 'POST',
                data: JSON.stringify({movie: updatedMovie}),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        showToast('Movie moved to Coming Soon!');
                        loadDashboardData();
                    } else {
                        showToast('Error moving movie');
                    }
                }
            });
        }
    }
}

function cancelEdit() {
    document.getElementById('editMovieForm').style.display = 'none';
}

// ==================== COMING SOON FUNCTIONS ====================
function addComingSoon(event) {
    event.preventDefault();
    
    // Prevent multiple submissions
    if (isSubmitting) {
        showToast('Please wait...');
        return;
    }
    
    // Get form elements
    const titleInput = document.getElementById('csTitle');
    const descriptionInput = document.getElementById('csDescription');
    const genreInput = document.getElementById('csGenre');
    const durationInput = document.getElementById('csDuration');
    const castInput = document.getElementById('csCast');
    const ratingSelect = document.getElementById('csRating');
    const releaseDateInput = document.getElementById('csReleaseDate');
    const trailerUrlInput = document.getElementById('csTrailerUrl');
    const posterInput = document.getElementById('csPoster');
    
    // Validate required fields
    if (!titleInput || !titleInput.value.trim()) {
        showToast('Please enter a movie title');
        return;
    }
    
    if (!releaseDateInput || !releaseDateInput.value) {
        showToast('Please select a release date');
        return;
    }
    
    const title = titleInput.value.trim();
    const description = descriptionInput ? descriptionInput.value.trim() : '';
    const genre = genreInput ? genreInput.value.trim() : '';
    const duration = durationInput ? durationInput.value.trim() : '';
    const cast = castInput ? castInput.value.trim() : '';
    const rating = ratingSelect ? ratingSelect.value : '';
    const release_date = releaseDateInput.value;
    const trailer_url = trailerUrlInput ? trailerUrlInput.value.trim() : '';
    const poster = posterInput ? posterInput.value : '';
    
    // CRITICAL: Make sure is_coming_soon is sent as 1 (number, not string)
    const newMovie = {
        title: title,
        description: description,
        genre: genre,
        duration: duration,
        cast: cast,
        rating: rating,
        release_date: release_date,
        trailer_url: trailer_url,
        poster: poster,
        is_coming_soon: 1  // This MUST be a number 1, not string "1"
    };
    
    // Set submitting flag
    isSubmitting = true;
    
    // Disable the submit button
    const submitBtn = event.target.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
    }
    
    $.ajax({
        url: 'api.php?action=admin_add_movie',
        type: 'POST',
        data: JSON.stringify({movie: newMovie}),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            // Reset submitting flag
            isSubmitting = false;
            
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            }
            
            if (response && response.success) {
                showToast('Coming soon movie added successfully!');
                
                // Reset the form
                if (event.target) {
                    event.target.reset();
                }
                
                // Reset file upload UI
                const fileNameSpan = document.getElementById('csPosterFileName');
                if (fileNameSpan) fileNameSpan.textContent = 'No file chosen';
                
                const preview = document.getElementById('csPosterPreview');
                if (preview) {
                    preview.style.display = 'none';
                    const img = preview.querySelector('img');
                    if (img) img.src = '';
                }
                
                const posterHidden = document.getElementById('csPoster');
                if (posterHidden) posterHidden.value = '';
                
                const fileInput = document.getElementById('csPosterFile');
                if (fileInput) fileInput.value = '';
                
                // Close the details/summary
                const details = event.target.closest('details');
                if (details) {
                    details.open = false;
                }
                
                // Immediately reload data and switch to coming soon tab
                loadDashboardData();
                
                // Force switch to coming soon tab after a short delay
                setTimeout(function() {
                    showMovieSection('coming');
                }, 300);
                
            } else {
                showToast('Error adding coming soon movie: ' + (response.message || 'Unknown error'));
                console.error('Server returned error:', response);
                
                // Reset submitting flag
                isSubmitting = false;
                
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error details:');
            console.error('Status:', status);
            console.error('Error:', error);
            console.error('Response Text:', xhr.responseText);
            
            // Reset submitting flag
            isSubmitting = false;
            
            // Re-enable submit button
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add';
            }
            
            showToast('Error adding coming soon movie');
        }
    });
}

function editComingSoon(id) {
    const movie = comingSoon.find(m => m.id == id);
    if (!movie) return;
    
    document.getElementById('editCsMovieId').value = movie.id;
    document.getElementById('editCsTitle').value = movie.title || '';
    document.getElementById('editCsDescription').value = movie.description || '';
    document.getElementById('editCsGenre').value = movie.genre || '';
    document.getElementById('editCsDuration').value = movie.duration || '';
    document.getElementById('editCsCast').value = movie.cast || '';
    document.getElementById('editCsRating').value = movie.rating || '';
    document.getElementById('editCsReleaseDate').value = movie.release_date || '';
    document.getElementById('editCsTrailerUrl').value = movie.trailer_url || '';
    document.getElementById('editCsPoster').value = movie.poster || '';
    
    // Show current poster preview if exists
    if (movie.poster) {
        const preview = document.getElementById('editCsPosterPreview');
        preview.style.display = 'block';
        preview.querySelector('img').src = movie.poster;
        preview.querySelector('img').style.display = 'block';
        document.getElementById('editCsPosterFileName').textContent = 'Current: ' + movie.poster.split('/').pop();
    }
    
    document.getElementById('editCsMovieForm').style.display = 'block';
}

function updateComingSoon(event) {
    event.preventDefault();
    const id = parseInt(document.getElementById('editCsMovieId').value);
    
    const updatedMovie = {
        id: id,
        title: document.getElementById('editCsTitle').value,
        description: document.getElementById('editCsDescription').value,
        genre: document.getElementById('editCsGenre').value,
        duration: document.getElementById('editCsDuration').value,
        cast: document.getElementById('editCsCast').value,
        rating: document.getElementById('editCsRating').value,
        release_date: document.getElementById('editCsReleaseDate').value,
        trailer_url: document.getElementById('editCsTrailerUrl').value,
        poster: document.getElementById('editCsPoster').value,
        is_coming_soon: 1
    };
    
    $.ajax({
        url: 'api.php?action=admin_update_movie',
        type: 'POST',
        data: JSON.stringify({movie: updatedMovie}),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                showToast('Coming soon movie updated!');
                loadDashboardData();
                cancelCsEdit();
            } else {
                showToast('Error updating movie');
            }
        }
    });
}

function deleteComingSoon(id) {
    if (confirm('Delete this coming soon movie?')) {
        $.ajax({
            url: 'api.php?action=admin_delete_movie&id=' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    showToast('Coming soon movie deleted!');
                    loadDashboardData();
                } else {
                    showToast('Error deleting movie');
                }
            }
        });
    }
}

function moveToCurrent(id) {
    if (confirm('Move this movie to Current Movies?')) {
        const movie = comingSoon.find(m => m.id == id);
        if (movie) {
            const updatedMovie = {
                id: id,
                is_coming_soon: 0
            };
            
            $.ajax({
                url: 'api.php?action=admin_update_movie',
                type: 'POST',
                data: JSON.stringify({movie: updatedMovie}),
                contentType: 'application/json',
                success: function(response) {
                    if (response.success) {
                        showToast('Movie moved to Current!');
                        loadDashboardData();
                    } else {
                        showToast('Error moving movie');
                    }
                }
            });
        }
    }
}

function cancelCsEdit() {
    document.getElementById('editCsMovieForm').style.display = 'none';
}

// ==================== USER FUNCTIONS ====================
function renderUsers() {
    const tbody = document.getElementById('usersBody');
    tbody.innerHTML = '';
    
    // Filter out admin user
    const regularUsers = users.filter(u => u.username !== 'admin');
    
    if (regularUsers.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--text-secondary);">No users found</td></tr>';
        return;
    }
    
    regularUsers.forEach(user => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="color: var(--text-primary); font-weight: 500;">${user.username || 'N/A'}</td>
            <td style="color: var(--accent-yellow); font-weight: 700;">${user.points || 0}</td>
            <td style="color: var(--accent-green); font-weight: 600;">${user.total_points_earned || 0}</td>
            <td style="color: var(--text-secondary);">${user.created_at ? new Date(user.created_at).toLocaleDateString() : 'N/A'}</td>
            <td>
                <span class="action-btn view" style="padding: 4px 8px; font-size: 0.7rem; cursor: pointer; color: var(--accent-blue);" onclick="viewUser(${user.id})">
                    <i class="fas fa-eye"></i>
                </span>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function viewUser(id) {
    const user = users.find(u => u.id == id);
    if (!user) return;
    
    document.getElementById('modalUsername').textContent = user.username || 'User';
    document.getElementById('modalPoints').textContent = user.points || 0;
    document.getElementById('modalTotalPoints').textContent = user.total_points_earned || 0;
    
    const userBookings = bookings.filter(b => b.user_id == id);
    const bookingsDiv = document.getElementById('modalBookings');
    bookingsDiv.innerHTML = '';
    
    if (userBookings.length === 0) {
        bookingsDiv.innerHTML = '<p style="text-align: center; color: var(--text-secondary);">No bookings yet</p>';
    } else {
        userBookings.slice(0, 3).forEach(booking => {
            const div = document.createElement('div');
            div.style.background = 'rgba(255, 255, 255, 0.05)';
            div.style.padding = '10px';
            div.style.borderRadius = '12px';
            div.style.marginBottom = '8px';
            div.style.border = '1px solid var(--glass-border)';
            div.innerHTML = `
                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">${booking.movie || 'Movie'}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">${booking.seats || 'N/A'} · ₱${parseFloat(booking.total || 0).toFixed(2)}</div>
                <div style="font-size: 0.7rem; color: var(--text-secondary);">${booking.booking_time ? new Date(booking.booking_time).toLocaleString() : 'N/A'}</div>
            `;
            bookingsDiv.appendChild(div);
        });
    }
    
    const userPromos = redeemedPromos.filter(p => p.user_id == id);
    const promosDiv = document.getElementById('modalPromos');
    promosDiv.innerHTML = '';
    
    if (userPromos.length === 0) {
        promosDiv.innerHTML = '<p style="text-align: center; color: var(--text-secondary);">No promos redeemed</p>';
    } else {
        userPromos.slice(0, 3).forEach(promo => {
            const div = document.createElement('div');
            div.style.background = 'rgba(255, 255, 255, 0.05)';
            div.style.padding = '10px';
            div.style.borderRadius = '12px';
            div.style.marginBottom = '8px';
            div.style.border = '1px solid var(--glass-border)';
            div.innerHTML = `
                <div style="font-weight: 600; color: var(--text-primary); font-size: 0.9rem;">${promo.promo_title || 'Promo'}</div>
                <div style="font-size: 0.75rem; color: var(--text-secondary);">${promo.points_spent || 0} points</div>
                <div style="font-size: 0.7rem; color: var(--text-secondary);">${promo.redeemed_at ? new Date(promo.redeemed_at).toLocaleDateString() : 'N/A'}</div>
            `;
            promosDiv.appendChild(div);
        });
    }
    
    document.getElementById('userModal').classList.add('active');
}

function closeModal() {
    document.getElementById('userModal').classList.remove('active');
}

// ==================== BOOKING FUNCTIONS ====================
function renderBookings(filterUser = '', filterMovie = '') {
    const tbody = document.getElementById('bookingsBody');
    tbody.innerHTML = '';
    
    // Make sure we have enriched bookings
    if (bookings.length > 0 && !bookings[0].username) {
        enrichBookingsWithUserData();
    }
    
    let filtered = [...bookings];
    
    if (filterUser) {
        filtered = filtered.filter(b => b.username === filterUser);
    }
    
    if (filterMovie) {
        filtered = filtered.filter(b => b.movie === filterMovie);
    }
    
    if (filtered.length === 0) {
        document.getElementById('noBookingsMessage').style.display = 'block';
        document.getElementById('bookingsTableContainer').style.display = 'none';
        document.getElementById('bookingSummary').style.display = 'none';
        return;
    }
    
    document.getElementById('noBookingsMessage').style.display = 'none';
    document.getElementById('bookingsTableContainer').style.display = 'block';
    document.getElementById('bookingSummary').style.display = 'block';
    
    filtered.forEach(booking => {
        const row = document.createElement('tr');
        
        // Format date nicely
        let bookingDate = 'N/A';
        if (booking.booking_time) {
            try {
                bookingDate = new Date(booking.booking_time).toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } catch(e) {
                bookingDate = booking.booking_time;
            }
        }
        
        row.innerHTML = `
            <td style="color: var(--text-primary); font-weight: 500;">${booking.username || 'Unknown User'}</td>
            <td style="color: var(--text-primary);">${booking.movie || 'N/A'}</td>
            <td style="color: var(--accent-yellow); font-weight: 600;">${booking.seats || 'N/A'}</td>
            <td style="color: var(--accent-green); font-weight: 700;">₱${parseFloat(booking.total || 0).toFixed(2)}</td>
            <td style="color: var(--text-secondary);">${bookingDate}</td>
        `;
        tbody.appendChild(row);
    });
    
    const totalRevenue = filtered.reduce((sum, b) => sum + (parseFloat(b.total) || 0), 0);
    document.getElementById('totalRevenueDisplay').textContent = '₱' + totalRevenue.toFixed(2);
    document.getElementById('totalBookingsDisplay').textContent = filtered.length;
}

function populateFilters() {
    const userSelect = document.getElementById('userFilter');
    const movieSelect = document.getElementById('movieFilter');
    
    userSelect.innerHTML = '<option value="">All Users</option>';
    movieSelect.innerHTML = '<option value="">All Movies</option>';
    
    // Make sure we have enriched bookings
    if (bookings.length > 0 && !bookings[0].username) {
        enrichBookingsWithUserData();
    }
    
    const uniqueUsers = [...new Set(bookings.map(b => b.username).filter(u => u && u !== 'Unknown User'))];
    uniqueUsers.sort().forEach(user => {
        const option = document.createElement('option');
        option.value = user;
        option.textContent = user;
        userSelect.appendChild(option);
    });
    
    const uniqueMovies = [...new Set(bookings.map(b => b.movie).filter(m => m))];
    uniqueMovies.sort().forEach(movie => {
        const option = document.createElement('option');
        option.value = movie;
        option.textContent = movie;
        movieSelect.appendChild(option);
    });
}

function applyFilters() {
    const user = document.getElementById('userFilter').value;
    const movie = document.getElementById('movieFilter').value;
    renderBookings(user, movie);
}

function clearFilters() {
    document.getElementById('userFilter').value = '';
    document.getElementById('movieFilter').value = '';
    renderBookings();
}

// ==================== PROMO FUNCTIONS ====================
function renderPromos() {
    const tbody = document.getElementById('promosBody');
    tbody.innerHTML = '';
    
    if (promos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--text-secondary);">No promos available</td></tr>';
        return;
    }
    
    promos.forEach(promo => {
        let discount = 'Free';
        if (promo.discount_amount) discount = '₱' + promo.discount_amount;
        else if (promo.discount_percentage) discount = promo.discount_percentage + '%';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="color: var(--text-primary); font-weight: 500;">${promo.title || 'Promo'}</td>
            <td style="color: var(--accent-yellow); font-weight: 700;">${promo.points_required || 0}</td>
            <td style="color: var(--accent-green); font-weight: 600;">${discount}</td>
            <td>
                ${promo.is_active ? 
                    '<span style="color: var(--accent-green);"><i class="fas fa-check-circle"></i> Active</span>' : 
                    '<span style="color: #ff3b6f;"><i class="fas fa-times-circle"></i> Inactive</span>'}
            </td>
            <td>
                <div style="display: flex; gap: 4px;">
                    <span class="action-btn edit" style="padding: 4px 8px; cursor: pointer; color: var(--accent-primary);" onclick="editPromo(${promo.id})">
                        <i class="fas fa-edit"></i>
                    </span>
                    <span class="action-btn delete" style="padding: 4px 8px; cursor: pointer; color: #ff3b6f;" onclick="deletePromo(${promo.id})">
                        <i class="fas fa-trash"></i>
                    </span>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function renderRedeemedPromos() {
    const tbody = document.getElementById('redeemedPromosBody');
    tbody.innerHTML = '';
    
    if (redeemedPromos.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--text-secondary);">No redeemed promos</td></tr>';
        return;
    }
    
    redeemedPromos.forEach(promo => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td style="color: var(--text-primary); font-weight: 500;">${promo.username || 'N/A'}</td>
            <td style="color: var(--text-primary);">${promo.promo_title || 'Promo'}</td>
            <td style="color: var(--accent-yellow); font-weight: 700;">${promo.points_spent || 0}</td>
            <td style="color: var(--text-secondary);">${promo.redeemed_at ? new Date(promo.redeemed_at).toLocaleDateString() : 'N/A'}</td>
        `;
        tbody.appendChild(row);
    });
}

function addPromo(event) {
    event.preventDefault();
    
    const newPromo = {
        title: document.getElementById('promoTitle').value,
        description: document.getElementById('promoDescription').value,
        points_required: parseInt(document.getElementById('promoPoints').value) || 0,
        discount_amount: document.getElementById('promoAmount').value ? parseFloat(document.getElementById('promoAmount').value) : null,
        discount_percentage: document.getElementById('promoPercentage').value ? parseFloat(document.getElementById('promoPercentage').value) : null,
        icon: document.getElementById('promoIcon').value || 'fa-gift',
        is_active: document.getElementById('promoActive').checked
    };
    
    $.ajax({
        url: 'api.php?action=admin_add_promo',
        type: 'POST',
        data: JSON.stringify({promo: newPromo}),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                showToast('Promo added successfully!');
                loadDashboardData();
                event.target.reset();
            } else {
                showToast('Error adding promo');
            }
        }
    });
}

function editPromo(id) {
    const promo = promos.find(p => p.id == id);
    if (!promo) return;
    
    document.getElementById('editPromoId').value = promo.id;
    document.getElementById('editPromoTitle').value = promo.title || '';
    document.getElementById('editPromoDescription').value = promo.description || '';
    document.getElementById('editPromoPoints').value = promo.points_required || 0;
    document.getElementById('editPromoAmount').value = promo.discount_amount || '';
    document.getElementById('editPromoPercentage').value = promo.discount_percentage || '';
    document.getElementById('editPromoIcon').value = promo.icon || 'fa-gift';
    document.getElementById('editPromoActive').checked = promo.is_active ? true : false;
    
    document.getElementById('editPromoForm').style.display = 'block';
}

function updatePromo(event) {
    event.preventDefault();
    const id = parseInt(document.getElementById('editPromoId').value);
    
    const updatedPromo = {
        id: id,
        title: document.getElementById('editPromoTitle').value,
        description: document.getElementById('editPromoDescription').value,
        points_required: parseInt(document.getElementById('editPromoPoints').value) || 0,
        discount_amount: document.getElementById('editPromoAmount').value ? parseFloat(document.getElementById('editPromoAmount').value) : null,
        discount_percentage: document.getElementById('editPromoPercentage').value ? parseFloat(document.getElementById('editPromoPercentage').value) : null,
        icon: document.getElementById('editPromoIcon').value || 'fa-gift',
        is_active: document.getElementById('editPromoActive').checked
    };
    
    $.ajax({
        url: 'api.php?action=admin_update_promo',
        type: 'POST',
        data: JSON.stringify({promo: updatedPromo}),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                showToast('Promo updated successfully!');
                loadDashboardData();
                cancelPromoEdit();
            } else {
                showToast('Error updating promo');
            }
        }
    });
}

function deletePromo(id) {
    if (confirm('Delete this promo?')) {
        $.ajax({
            url: 'api.php?action=admin_delete_promo&id=' + id,
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    showToast('Promo deleted successfully!');
                    loadDashboardData();
                } else {
                    showToast('Error deleting promo');
                }
            }
        });
    }
}

function cancelPromoEdit() {
    document.getElementById('editPromoForm').style.display = 'none';
}

// ==================== SEAT FUNCTIONS ====================
function populateMovieSelect() {
    const select = document.getElementById('mobileMovieSelect');
    select.innerHTML = '<option value="">-- Choose Movie --</option>';
    
    [...movies, ...comingSoon].forEach(movie => {
        const option = document.createElement('option');
        option.value = movie.id;
        option.textContent = movie.title || 'Untitled';
        select.appendChild(option);
    });
}

function loadMobileSeats() {
    const movieId = document.getElementById('mobileMovieSelect').value;
    const showDate = document.getElementById('mobileShowDate').value;
    const showTime = document.getElementById('mobileShowTime').value;
    
    if (!movieId) {
        showToast('Please select a movie');
        return;
    }
    
    if (!showDate) {
        showToast('Please select a date');
        return;
    }

    document.getElementById('mobileSeatControls').style.display = 'block';
    
    const movieSelect = document.getElementById('mobileMovieSelect');
    const selectedMovie = movieSelect.options[movieSelect.selectedIndex].text;
    
    showToast(`Loading seats for ${selectedMovie} - ${showDate} ${showTime}`);

    // Load occupied seats from database
    $.getJSON(`api.php?action=get_occupied_seats&movie=${encodeURIComponent(selectedMovie)}&date=${showDate}&time=${encodeURIComponent(showTime)}`, function(response) {
        console.log('Seat data received:', response); // Debug log
        
        // Handle both array and object responses
        if (Array.isArray(response)) {
            // Old format - just an array of seats
            occupiedSeats = response;
        } else if (response && response.occupied_seats) {
            // New format - object with occupied_seats array
            occupiedSeats = response.occupied_seats;
        } else {
            occupiedSeats = [];
        }
        
        console.log('Occupied seats:', occupiedSeats);
        renderMobileSeats();
        
        // Reset zoom when loading new seats
        setTimeout(() => {
            resetZoom();
        }, 100);
    }).fail(function(xhr, status, error) {
        console.error('Error loading seats:', error);
        console.error('Response:', xhr.responseText);
        showToast('Error loading seats');
        occupiedSeats = [];
        renderMobileSeats();
    });
}

// Helper function to create seat click handler
function createSeatHandler(seat, selectedSeats, price) {
    return function() {
        if ($(this).hasClass('booked') || $(this).hasClass('blocked')) return;
        $(this).toggleClass('selected');
        const s = $(this).attr('data-seat');
        if ($(this).hasClass('selected')) {
            selectedSeats.push(s);
        } else {
            const index = selectedSeats.indexOf(s);
            if (index > -1) selectedSeats.splice(index, 1);
        }
    };
}

// Seat layout function
function generateSeatLayout(grid, selectedSeats, occupied, price) {
    // Upper rows (U through I) - L, K, J, I are short rows
    const upperRows = ['U', 'T', 'S', 'R', 'Q', 'P', 'O', 'N', 'M', 'L', 'K', 'J', 'I'];
    
    upperRows.forEach((row, index) => {
        const rowContainer = $('<div class="seat-row-container"></div>');
        
        // Check if this is a short row (L, K, J, I)
        const isShortRow = ['L', 'K', 'J', 'I'].includes(row);
        
        // Add left letter - move only I-L rows closer to number 1 column
        if (isShortRow) {
            // Move ONLY the left letters closer to the numbers
            rowContainer.append(`<div class="row-label-left" style="transform: translateX(110px);">${row}</div>`);
        } else {
            rowContainer.append(`<div class="row-label-left">${row}</div>`);
        }
        
        const rowDiv = $('<div class="seat-row"></div>');
        
        if (isShortRow) {
            // For short rows - seats remain EXACTLY the same, no changes
            for (let i = 1; i <= 4; i++) {
                const emptySeat = $('<div class="seat" style="background: transparent; box-shadow: none; border: none; cursor: default;"></div>').text('');
                rowDiv.append(emptySeat);
            }
            
            // Left block - seats 1-5 - positions unchanged
            for (let num = 1; num <= 5; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            // Rest of the row - completely unchanged
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 6; num <= 16; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 17; num <= 21; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            for (let i = 26; i <= 26; i++) {
                const emptySeat = $('<div class="seat" style="background: transparent; box-shadow: none; border: none; cursor: default;"></div>').text('');
                rowDiv.append(emptySeat);
            }
        } else {
            // Normal rows - completely unchanged
            for (let num = 1; num <= 9; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 10; num <= 20; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 21; num <= 29; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('booked');
                } else if (seatConfig[seatId] === 'blocked') {
                    seat.addClass('blocked');
                } else {
                    seat.addClass('available');
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
        }
        
        rowContainer.append(rowDiv);
        
        // Add right letter - move only I-L rows closer to the numbers from the right side
        if (isShortRow) {
            // Move ONLY the right letters closer to the numbers (from right to left)
            rowContainer.append(`<div class="row-label-right" style="transform: translateX(-30px);">${row}</div>`);
        } else {
            rowContainer.append(`<div class="row-label-right">${row}</div>`);
        }
        
        grid.append(rowContainer);
    });

    // Add space between sections
    grid.append('<div style="height: 30px; width: 100%;"></div>');

    // Lower rows (H through A) - apply letter positioning with -80px
    const lowerRows = ['H', 'G', 'F', 'E', 'D', 'C', 'B', 'A'];

    lowerRows.forEach(row => {
        const rowContainer = $('<div class="seat-row-container"></div>');
        
        // Add left letter - move ALL lower rows with -80px
        rowContainer.append(`<div class="row-label-left" style="transform: translateX(50px);">${row}</div>`);
        
        const rowDiv = $('<div class="seat-row"></div>');
        
        for (let i = 1; i <= 2; i++) {
            const emptySeat = $('<div class="seat" style="background: transparent; box-shadow: none; border: none; cursor: default;"></div>').text('');
            rowDiv.append(emptySeat);
        }
        
        for (let num = 1; num <= 13; num++) {
            const seatId = row + num;
            const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
            if (occupied.includes(seatId)) {
                seat.addClass('booked');
            } else if (seatConfig[seatId] === 'blocked') {
                seat.addClass('blocked');
            } else {
                seat.addClass('available');
                seat.click(createSeatHandler(seat, selectedSeats, price));
            }
            rowDiv.append(seat);
        }
        
        rowDiv.append('<div style="width:10px"></div>');
        
        for (let num = 14; num <= 26; num++) {
            const seatId = row + num;
            const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
            if (occupied.includes(seatId)) {
                seat.addClass('booked');
            } else if (seatConfig[seatId] === 'blocked') {
                seat.addClass('blocked');
            } else {
                seat.addClass('available');
                seat.click(createSeatHandler(seat, selectedSeats, price));
            }
            rowDiv.append(seat);
        }
        
        for (let i = 1; i <= 3; i++) {
            const emptySeat = $('<div class="seat" style="background: transparent; box-shadow: none; border: none; cursor: default;"></div>').text('');
            rowDiv.append(emptySeat);
        }
        
        rowContainer.append(rowDiv);
        
        // Add right letter - move ALL lower rows with -80px
        rowContainer.append(`<div class="row-label-right" style="transform: translateX(-80px);">${row}</div>`);
        
        grid.append(rowContainer);
    });
}

function renderMobileSeats() {
    const seatGrid = $('#mobileSeatGrid');
    seatGrid.empty();
    
    const selectedSeats = [];
    
    // Make sure occupiedSeats is an array
    if (!Array.isArray(occupiedSeats)) {
        console.warn('occupiedSeats is not an array, resetting to empty array');
        occupiedSeats = [];
    }
    
    console.log('Rendering seats with occupied seats:', occupiedSeats);
    
    // Pass the occupied seats to the layout function
    generateSeatLayout(seatGrid, selectedSeats, occupiedSeats, 350);
}

function saveMobileSeats() {
    showToast('Seat management feature coming soon');
}

function resetMobileSeats() {
    showToast('Seat reset feature coming soon');
}

// ==================== TOAST FUNCTIONS ====================
function showToast(message) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMessage').textContent = message;
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}

// ==================== FILE UPLOAD FUNCTIONS ====================

// Generic file upload function
function uploadFile(file, callback) {
    const formData = new FormData();
    formData.append('image', file);
    
    const baseUrl = window.location.origin + '/mobile_app/';
    const uploadUrl = baseUrl + 'upload.php';
    
    $.ajax({
        url: uploadUrl,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch(e) {
                    callback({success: false, message: 'Invalid server response'});
                    return;
                }
            }
            
            callback(response);
        },
        error: function(xhr, status, error) {
            callback({success: false, message: 'Upload failed: ' + error});
        }
    });
}

// ==================== TRAILER FUNCTIONS ====================
function playTrailer(title, trailerUrl) {
    if (!trailerUrl) {
        showToast('No trailer available for this movie');
        return;
    }
    
    document.getElementById('trailerModalTitle').textContent = title + ' - Trailer';
    const iframe = document.getElementById('trailerIframe');
    iframe.src = 'https://www.youtube.com/embed/' + trailerUrl + '?autoplay=1';
    
    document.getElementById('trailerModal').classList.add('active');
}

function closeTrailerModal() {
    document.getElementById('trailerModal').classList.remove('active');
    const iframe = document.getElementById('trailerIframe');
    iframe.src = ''; // Stop video when closing
}

// ==================== INITIALIZATION ====================
$(document).ready(function() {
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('mobileShowDate').value = today;
    
    <?php if (isset($_SESSION['user_id']) && $_SESSION['username'] === 'admin'): ?>
    // Admin is logged in
    document.getElementById('loginScreen').style.display = 'none';
    document.getElementById('appContainer').style.display = 'block';
    loadDashboardData();
    updateHomeScreen();
    
    const slider = document.getElementById('statsSlider');
    slider.addEventListener('scroll', updateSliderIndicators);
    
    // Auto-load seats event listeners
    const movieSelect = document.getElementById('mobileMovieSelect');
    if (movieSelect) {
        movieSelect.addEventListener('change', function() {
            if (this.value && document.getElementById('mobileShowDate').value) {
                loadMobileSeats();
            }
        });
    }

    const showDate = document.getElementById('mobileShowDate');
    if (showDate) {
        showDate.addEventListener('change', function() {
            if (this.value && document.getElementById('mobileMovieSelect').value) {
                loadMobileSeats();
            }
        });
    }

    const showTime = document.getElementById('mobileShowTime');
    if (showTime) {
        showTime.addEventListener('change', function() {
            if (document.getElementById('mobileMovieSelect').value && 
                document.getElementById('mobileShowDate').value) {
                loadMobileSeats();
            }
        });
    }

    // ===== FILE UPLOAD EVENT LISTENERS =====
    
    // For Add Movie form
    const moviePosterFile = document.getElementById('moviePosterFile');
    if (moviePosterFile) {
        const newMoviePosterFile = moviePosterFile.cloneNode(true);
        moviePosterFile.parentNode.replaceChild(newMoviePosterFile, moviePosterFile);
        
        newMoviePosterFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast('File too large! Maximum size is 5MB');
                    this.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    showToast('Please select an image file');
                    this.value = '';
                    return;
                }
                
                document.getElementById('moviePosterFileName').textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('moviePosterPreview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload file
                showToast('Uploading image...');
                uploadFile(file, function(response) {
                    if (response && response.success) {
                        document.getElementById('moviePoster').value = response.filepath;
                        showToast('✅ Image uploaded successfully!');
                    } else {
                        const errorMsg = response ? (response.message || 'Unknown error') : 'No response from server';
                        showToast('❌ Upload failed: ' + errorMsg);
                    }
                });
            }
        });
    }

    // For Edit Movie form
    const editMoviePosterFile = document.getElementById('editMoviePosterFile');
    if (editMoviePosterFile) {
        editMoviePosterFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('editMoviePosterFileName').textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('editMoviePosterPreview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload file
                showToast('Uploading image...');
                uploadFile(file, function(response) {
                    if (response && response.success) {
                        document.getElementById('editPoster').value = response.filepath;
                        showToast('✅ Image uploaded successfully!');
                    } else {
                        const errorMsg = response ? (response.message || 'Unknown error') : 'No response from server';
                        showToast('❌ Upload failed: ' + errorMsg);
                    }
                });
            }
        });
    }

    // For Add Coming Soon form
    const csPosterFile = document.getElementById('csPosterFile');
    if (csPosterFile) {
        csPosterFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('csPosterFileName').textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('csPosterPreview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload file
                showToast('Uploading image...');
                uploadFile(file, function(response) {
                    if (response && response.success) {
                        document.getElementById('csPoster').value = response.filepath;
                        showToast('✅ Image uploaded successfully!');
                    } else {
                        const errorMsg = response ? (response.message || 'Unknown error') : 'No response from server';
                        showToast('❌ Upload failed: ' + errorMsg);
                    }
                });
            }
        });
    }

    // For Edit Coming Soon form
    const editCsPosterFile = document.getElementById('editCsPosterFile');
    if (editCsPosterFile) {
        editCsPosterFile.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                document.getElementById('editCsPosterFileName').textContent = file.name;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('editCsPosterPreview');
                    preview.style.display = 'block';
                    preview.querySelector('img').src = e.target.result;
                };
                reader.readAsDataURL(file);
                
                // Upload file
                showToast('Uploading image...');
                uploadFile(file, function(response) {
                    if (response && response.success) {
                        document.getElementById('editCsPoster').value = response.filepath;
                        showToast('✅ Image uploaded successfully!');
                    } else {
                        const errorMsg = response ? (response.message || 'Unknown error') : 'No response from server';
                        showToast('❌ Upload failed: ' + errorMsg);
                    }
                });
            }
        });
    }
    
    <?php else: ?>
    document.getElementById('loginScreen').style.display = 'flex';
    document.getElementById('appContainer').style.display = 'none';
    
    document.getElementById('username').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleLogin();
        }
    });
    
    document.getElementById('password').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            handleLogin();
        }
    });
    <?php endif; ?>
});
</script>

</body>
</html>