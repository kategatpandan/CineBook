<?php
// cinebook.php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Not logged in, show login screen
    $showLogin = true;
} else {
    // Logged in
    $showLogin = false;
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $userPoints = $_SESSION['points'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=3.0, user-scalable=yes">
<title>CineBook - Mobile Cinema Booking</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="manifest" href="/manifest.json">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="CineBook">
<meta name="theme-color" content="#ff8c42">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="CineBook">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<style>
/* ALL YOUR EXISTING CSS STYLES - KEEP EXACTLY AS IS */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    -webkit-tap-highlight-color: transparent;
}

html, body {
    height: 100%;
    width: 100%;
    font-size: 14px;
    background: #0a0a0f;
    color: #ffffff;
    overflow-x: hidden;
    font-family: 'Inter', sans-serif;
    scroll-behavior: smooth;
}

/* Allow zooming */
body {
    touch-action: pan-y pinch-zoom;
}

/* ===== MODERN VARIABLES ===== */
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
    --accent-navy: #1e3a8a; 
    --accent-orange: #ff8c42; 
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
}

/* ===== LIGHT MODE ===== */
[data-theme="light"] {
    --bg-primary: #f8f9fa;
    --bg-secondary: #ffffff;
    --bg-tertiary: #f1f3f5;
    --bg-card: #ffffff;
    --bg-card-hover: #f8f9fa;
    --text-primary: #212529; 
    --text-secondary: #6c757d;  
    --accent-primary: #ff8c42; 
    --accent-secondary: #1e3a8a; 
    --accent-gradient: linear-gradient(135deg, #1e3a8a, #ff8c42);
    --accent-glow: 0 0 20px rgba(30, 58, 138, 0.2);
    --border-color: rgba(0, 0, 0, 0.08);
    --shadow-color: rgba(0, 0, 0, 0.1);
    --shadow-elevation: 0 8px 30px rgba(0, 0, 0, 0.1);
    --seat-available: #e9ecef;
    --seat-selected: #ff8c42; 
    --seat-occupied: #1e3a8a; 
    --glass-effect: rgba(0, 0, 0, 0.02);
    --glass-border: rgba(0, 0, 0, 0.05);

    /* Additional light mode specific fixes */
    background: var(--bg-primary);
    color: var(--text-primary);
}

/* Light mode specific overrides */
[data-theme="light"] .navbar {
    background: rgba(255, 255, 255, 0.9);
    border-bottom-color: var(--glass-border);
}

[data-theme="light"] .navbar .logo span {
    color: var(--text-primary); 
    -webkit-text-fill-color: initial;
    background: none;
    -webkit-background-clip: initial;
    color: var(--text-primary);
}

[data-theme="light"] .bottom-nav {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

[data-theme="light"] .bottom-nav-item {
    color: var(--text-secondary);
}

[data-theme="light"] .bottom-nav-item span {
    color: var(--text-secondary);
}

[data-theme="light"] .bottom-nav-item.active {
    color: var(--accent-primary);
}

[data-theme="light"] .bottom-nav-item.active span {
    color: var(--accent-primary);
}

[data-theme="light"] .bottom-nav-item.active::before {
    background: radial-gradient(circle, rgba(255, 140, 66, 0.2) 0%, transparent 70%);
}

[data-theme="light"] .auth-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid var(--glass-border);
}

[data-theme="light"] .auth-card h2 {
    color: var(--text-primary); 
}

[data-theme="light"] .auth-card p {
    color: var(--text-secondary);
}

[data-theme="light"] .auth-card input {
    background: #f8f9fa;
    color: var(--text-primary); 
    border-color: var(--border-color);
}

[data-theme="light"] .auth-card input::placeholder {
    color: var(--text-secondary);
}

[data-theme="light"] .auth-card input:focus {
    border-color: var(--accent-primary);
}

[data-theme="light"] .movie-card {
    background: var(--bg-card);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

[data-theme="light"] .movie-card h3 {
    color: var(--text-primary);
}

[data-theme="light"] .movie-card p {
    color: var(--text-secondary);
}

[data-theme="light"] .movie-card .price {
    color: var(--accent-primary);
}

[data-theme="light"] .date-card,
[data-theme="light"] .time-card,
[data-theme="light"] .promo-card,
[data-theme="light"] .booking-card,
[data-theme="light"] .payment-method,
[data-theme="light"] .payment-form,
[data-theme="light"] .ticket-card {
    background: var(--bg-card);
    border-color: var(--glass-border);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

[data-theme="light"] .date-card .date-num,
[data-theme="light"] .date-card .day,
[data-theme="light"] .date-card .month {
    color: var(--text-primary); 
}

[data-theme="light"] .date-card.active,
[data-theme="light"] .time-card.active {
    background: var(--accent-gradient);
}

[data-theme="light"] .date-card.active .date-num,
[data-theme="light"] .date-card.active .day,
[data-theme="light"] .date-card.active .month,
[data-theme="light"] .time-card.active .time-value,
[data-theme="light"] .time-card.active .time-period {
    color: white; 
}

[data-theme="light"] .time-card .time-value,
[data-theme="light"] .time-card .time-period {
    color: var(--text-primary); 
}

[data-theme="light"] .seat {
    background: var(--seat-available);
    color: var(--text-primary);
}

[data-theme="light"] .seat.occupied {
    background: var(--seat-occupied);
    color: white; 
    opacity: 0.7;
}

[data-theme="light"] .seat.selected {
    background: var(--seat-selected);
    color: white;
}

[data-theme="light"] .seat-legend {
    background: var(--bg-card);
}

[data-theme="light"] .seat-legend span {
    color: var(--text-secondary);
}

[data-theme="light"] .screen {
    background: linear-gradient(180deg, #e9ecef, #dee2e6);
    color: var(--accent-secondary); 
}

[data-theme="light"] .show-info {
    background: var(--bg-card) !important;
}

[data-theme="light"] .show-info h3 {
    color: var(--text-primary); 
}

[data-theme="light"] .show-info p {
    color: var(--text-secondary);
}

[data-theme="light"] .movie-content-section {
    background: var(--bg-secondary);
}

[data-theme="light"] .movie-description {
    color: var(--text-secondary);
}

[data-theme="light"] .detail-label {
    color: var(--text-secondary);
}

[data-theme="light"] .detail-value {
    color: var(--text-primary); 
}

[data-theme="light"] .pricing-tag {
    border-color: var(--glass-border);
}

[data-theme="light"] .pricing-tag .price {
    color: var(--accent-primary);
}

[data-theme="light"] .pricing-tag .points {
    background: rgba(6, 214, 160, 0.1);
    color: #06d6a0;
}

[data-theme="light"] .selector-label {
    color: var(--text-primary); 
}

[data-theme="light"] .promo-badge {
    background: var(--accent-gradient);
    color: white; 
}

[data-theme="light"] .promo-title {
    color: var(--text-primary); 
}

[data-theme="light"] .promo-description {
    color: var(--text-secondary);
}

[data-theme="light"] .points-required {
    color: var(--accent-primary);
}

[data-theme="light"] .rewards-history {
    background: var(--bg-card);
}

[data-theme="light"] .rewards-history h3 {
    -webkit-text-fill-color: initial;
    background: none;
    color: var(--text-primary); 
}

[data-theme="light"] .reward-item {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .reward-title {
    color: var(--text-primary); 
}

[data-theme="light"] .reward-date {
    color: var(--text-secondary);
}

[data-theme="light"] .booking-header {
    border-bottom-color: var(--glass-border);
}

[data-theme="light"] .booking-movie {
    color: var(--text-primary); 
}

[data-theme="light"] .booking-date {
    background: rgba(0, 0, 0, 0.03);
    color: var(--text-secondary);
}

[data-theme="light"] .booking-detail .detail-label {
    color: var(--text-secondary);
}

[data-theme="light"] .booking-detail .detail-value {
    color: var(--text-primary); 
}

[data-theme="light"] .action-btn-small {
    background: rgba(0, 0, 0, 0.05);
    color: var(--text-primary); 
}

[data-theme="light"] .action-btn-small.btn-primary {
    background: var(--accent-gradient);
    color: white; 
}

[data-theme="light"] .payment-method {
    background: var(--bg-card);
}

[data-theme="light"] .payment-method.selected {
    background: rgba(255, 140, 66, 0.1);
}

[data-theme="light"] .payment-method h3 {
    color: var(--text-primary); 
}

[data-theme="light"] .payment-method p {
    color: var(--text-secondary);
}

[data-theme="light"] .payment-form {
    background: var(--bg-card);
}

[data-theme="light"] .payment-form h3 {
    -webkit-text-fill-color: initial;
    background: none;
    color: var(--text-primary); 
}

[data-theme="light"] .payment-form input {
    background: #f8f9fa;
    color: var(--text-primary); 
    border-color: var(--border-color);
}

[data-theme="light"] .payment-form input::placeholder {
    color: var(--text-secondary);
}

[data-theme="light"] .payment-summary {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .payment-summary h4 {
    color: var(--text-primary); 
}

[data-theme="light"] .summary-item {
    color: var(--text-secondary);
}

[data-theme="light"] .summary-item.total {
    color: var(--accent-primary);
    border-top-color: var(--glass-border);
}

[data-theme="light"] .ticket-info {
    background: var(--bg-card);
}

[data-theme="light"] .info-group {
    border-bottom-color: var(--glass-border);
}

[data-theme="light"] .info-label {
    color: var(--text-secondary);
}

[data-theme="light"] .info-value {
    color: var(--text-primary); 
}

[data-theme="light"] .ticket-qr {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .reference-label {
    color: var(--text-secondary);
}

[data-theme="light"] .reference-value {
    color: var(--accent-primary);
}

[data-theme="light"] .ticket-actions {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .action-btn.btn-secondary {
    background: rgba(0, 0, 0, 0.05);
    color: var(--text-primary); 
    border-color: var(--glass-border);
}

[data-theme="light"] .action-btn.btn-primary {
    color: white; 
}

[data-theme="light"] .home-faq-section h2 {
    -webkit-text-fill-color: initial;
    background: none;
    color: var(--text-primary); 
}

[data-theme="light"] .home-faq-item {
    background: var(--bg-card);
}

[data-theme="light"] .home-faq-question {
    background: rgba(0, 0, 0, 0.02);
    color: var(--text-primary); 
}

[data-theme="light"] .home-faq-answer {
    color: var(--text-secondary);
}

[data-theme="light"] .home-faq-item.active .home-faq-answer {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .home-important-notice {
    background: rgba(255, 140, 66, 0.1);
    border-color: rgba(255, 140, 66, 0.3);
}

[data-theme="light"] .home-important-notice h3 {
    color: var(--accent-primary);
}

[data-theme="light"] .home-important-notice p {
    color: var(--text-secondary);
}

[data-theme="light"] .no-results,
[data-theme="light"] .no-bookings,
[data-theme="light"] .no-rewards {
    background: var(--bg-card);
}

[data-theme="light"] .no-results h3,
[data-theme="light"] .no-bookings h3 {
    color: var(--text-primary); 
}

[data-theme="light"] .no-results p,
[data-theme="light"] .no-bookings p {
    color: var(--text-secondary);
}

[data-theme="light"] .back-btn {
    background: rgba(0, 0, 0, 0.1);
    color: var(--text-primary); 
    border-color: var(--glass-border);
}

[data-theme="light"] .back-btn:hover {
    background: var(--accent-primary);
    color: white; 
}

[data-theme="light"] #searchOverlay {
    background: rgba(255, 255, 255, 0.95);
}

[data-theme="light"] #searchOverlay input {
    background: white;
    color: var(--text-primary); 
    border-color: var(--border-color);
}

[data-theme="light"] #searchOverlay input::placeholder {
    color: var(--text-secondary);
}

[data-theme="light"] #searchOverlay input:focus {
    border-color: var(--accent-primary);
}

[data-theme="light"] .floating-theme-toggle {
    background: var(--accent-gradient);
    color: white; 
}

[data-theme="light"] .toast {
    background: white;
    color: var(--text-primary); 
    border-left-color: var(--accent-primary);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

[data-theme="light"] .toast.success {
    border-left-color: var(--accent-green);
}

[data-theme="light"] .toast.error {
    border-left-color: #ff3b6f;
}

[data-theme="light"] .modal-content,
[data-theme="light"] .reward-ticket-content {
    background: white;
}

[data-theme="light"] .modal-content h2,
[data-theme="light"] .modal-content p {
    color: var(--text-primary); 
}

[data-theme="light"] .modal-content p:not(h2) {
    color: var(--text-secondary);
}

[data-theme="light"] .trailer-modal-content {
    background: #000;
}

[data-theme="light"] .close-trailer {
    background: rgba(255, 255, 255, 0.2);
    color: white; 
}

[data-theme="light"] .close-trailer:hover {
    background: var(--accent-primary);
}

[data-theme="light"] .qr-code {
    background: white;
    border: 1px solid var(--glass-border);
}

[data-theme="light"] .rating-badge {
    color: white; 
}

[data-theme="light"] .rating-badge.pg13 {
    color: #333; 
}

[data-theme="light"] .rating-badge.r {
    background: var(--rating-r);
}

[data-theme="light"] .rating-badge.nc17 {
    background: var(--rating-nc17);
}

[data-theme="light"] .coming-soon-badge {
    color: white; 
}

[data-theme="light"] .password-requirements {
    background: rgba(0, 0, 0, 0.02);
}

[data-theme="light"] .password-requirements div {
    color: var(--text-secondary);
}

[data-theme="light"] .password-requirements div i.fa-check-circle {
    color: var(--accent-green);
}

[data-theme="light"] .password-requirements div i.fa-times-circle {
    color: #ff3b6f;
}

[data-theme="light"] #pointsDisplay {
    background: rgba(255, 140, 66, 0.15);
    border-color: rgba(255, 140, 66, 0.3);
    color: var(--accent-primary);
}

[data-theme="light"] #pointsDisplay span {
    color: var(--accent-primary);
}

[data-theme="light"] #pointsDisplay i {
    color: var(--accent-primary);
}

[data-theme="light"] .logout-btn {
    background: rgba(255, 140, 66, 0.1);
    color: var(--accent-primary);
}

[data-theme="light"] .logout-btn:hover {
    background: rgba(255, 140, 66, 0.2);
}

[data-theme="light"] .theme-toggle,
[data-theme="light"] .search-toggle {
    background: rgba(0, 0, 0, 0.05);
    color: var(--text-primary); 
}

[data-theme="light"] .theme-toggle:hover,
[data-theme="light"] .search-toggle:hover {
    background: rgba(255, 140, 66, 0.1);
    color: var(--accent-primary);
}

[data-theme="light"] .screen-header h1,
[data-theme="light"] .screen-header p {
    color: white; 
}

[data-theme="light"] .movie-header-title,
[data-theme="light"] .movie-header-subtitle,
[data-theme="light"] .movie-header-meta {
    color: white; 
}

[data-theme="light"] .auth-logo-text {
    -webkit-text-fill-color: initial;
    background: none;
    color: var(--text-primary); 
}

[data-theme="light"] .auth-card .error-msg {
    color: var(--accent-primary);
}

[data-theme="light"] .cast-mini-name {
    color: var(--text-primary); 
}

[data-theme="light"] .cast-mini-character {
    color: var(--text-secondary);
}

[data-theme="light"] .row-label-left,
[data-theme="light"] .row-label-right {
    color: var(--accent-primary);
}

/* Additional light mode fixes for ratings and rewards */
[data-theme="light"] .rating-badge {
    color: white; 
}

[data-theme="light"] .rating-badge.pg13 {
    color: #333; 
}

[data-theme="light"] #modalRating {
    color: white !important; 
}

[data-theme="light"] .movie-header-rating {
    color: white;
}

[data-theme="light"] .no-rewards h3 {
    color: var(--text-primary); 
}

[data-theme="light"] .no-rewards p {
    color: var(--text-secondary); 
}

[data-theme="light"] .reward-ticket-detail .reward-ticket-value {
    color: var(--text-primary); 
}

[data-theme="light"] .reward-ticket-detail .reward-ticket-label {
    color: var(--text-secondary);
}

[data-theme="light"] #ticketRewardName,
[data-theme="light"] #ticketRewardDescription,
[data-theme="light"] #ticketPointsSpent,
[data-theme="light"] #ticketRedeemedDate,
[data-theme="light"] #ticketValidUntil,
[data-theme="light"] #ticketReference {
    color: var(--text-primary);
}

[data-theme="light"] .reward-ticket-note {
    color: var(--text-primary);
}

[data-theme="light"] .reward-ticket-note i {
    color: var(--accent-primary); 
}

[data-theme="light"] .modal-content .movie-rating-badge {
    color: white; 
}

[data-theme="light"] .modal-content .movie-rating-badge.pg13 {
    color: #333; 
}

[data-theme="light"] .modal-content h2 {
    color: var(--accent-primary); 
}

[data-theme="light"] .modal-content p {
    color: var(--text-secondary); 
}

[data-theme="light"] .modal-content #modalGenre,
[data-theme="light"] .modal-content #modalDuration {
    color: var(--text-primary); 
}

[data-theme="light"] .modal-content #modalReleaseDate {
    color: var(--accent-primary); 
}

/* ===== MODERN NAVBAR ===== */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    background: rgba(18, 18, 26, 0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    color: var(--text-primary);
    padding: 12px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 1000;
    border-bottom: 1px solid var(--glass-border);
    height: 70px;
}

[data-theme="light"] .navbar {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

/* Hide navbar on auth screens */
body.auth-screen .navbar {
    display: none;
}

.logo {
    display: flex;
    align-items: center;
    gap: 12px;
    height: 40px;
}

.logo-jpg {
    width: 40px;
    height: 40px;
    object-fit: contain;
    border-radius: 12px;
    filter: drop-shadow(0 4px 10px rgba(255, 59, 111, 0.3));
}

.logo span {
    font-size: 1.3rem;
    font-weight: 700;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    letter-spacing: -0.5px;
}

/* ===== MODERN BOTTOM NAVIGATION ===== */
.bottom-nav {
    position: fixed;
    bottom: 16px;
    left: 16px;
    right: 16px;
    width: auto;
    background: rgba(18, 18, 26, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    display: flex;
    justify-content: space-around;
    align-items: center;
    padding: 8px 12px;
    border-radius: 40px;
    border: 1px solid var(--glass-border);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 59, 111, 0.1);
    z-index: 1000;
    height: 70px;
    margin: 0 auto;
    max-width: 400px;
}

[data-theme="light"] .bottom-nav {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border: 1px solid rgba(0, 0, 0, 0.08);
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(255, 59, 111, 0.2);
}

.bottom-nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 4px;
    color: var(--text-secondary);
    font-size: 0.7rem;
    cursor: pointer;
    padding: 8px 0;
    border-radius: 30px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    flex: 1;
    position: relative;
}

.bottom-nav-item i {
    font-size: 1.4rem;
    margin-bottom: 2px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.bottom-nav-item span {
    font-weight: 500;
    letter-spacing: 0.3px;
    opacity: 0.8;
    transition: all 0.3s ease;
}

.bottom-nav-item.active {
    color: var(--accent-primary);
}

.bottom-nav-item.active i {
    transform: translateY(-2px);
    filter: drop-shadow(0 4px 8px rgba(255, 59, 111, 0.4));
}

.bottom-nav-item.active span {
    opacity: 1;
    font-weight: 600;
}

.bottom-nav-item:hover i {
    transform: translateY(-2px);
}

.bottom-nav-item:active {
    transform: scale(0.95);
}

/* Add a glowing orb behind active icon */
.bottom-nav-item.active::before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 40px;
    background: radial-gradient(circle, rgba(255, 59, 111, 0.2) 0%, transparent 70%);
    border-radius: 50%;
    z-index: -1;
    animation: glowPulse 2s infinite;
}

@keyframes glowPulse {
    0% {
        opacity: 0.5;
        transform: translateX(-50%) scale(0.8);
    }
    50% {
        opacity: 1;
        transform: translateX(-50%) scale(1.2);
    }
    100% {
        opacity: 0.5;
        transform: translateX(-50%) scale(0.8);
    }
}

/* Responsive adjustments */
@media (max-width: 380px) {
    .bottom-nav {
        bottom: 12px;
        left: 12px;
        right: 12px;
        height: 65px;
        padding: 6px 10px;
    }
    
    .bottom-nav-item i {
        font-size: 1.3rem;
    }
    
    .bottom-nav-item span {
        font-size: 0.65rem;
    }
}

/* Optional: Add a floating animation */
.bottom-nav {
    animation: slideUp 0.5s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
}

#pointsDisplay {
    background: rgba(255, 59, 111, 0.15);
    padding: 8px 14px;
    border-radius: 30px;
    font-weight: 600;
    border: 1px solid rgba(255, 59, 111, 0.3);
    color: var(--accent-yellow);
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

#pointsDisplay i {
    color: var(--accent-yellow);
    font-size: 0.9rem;
}

.theme-toggle, .search-toggle, .logout-btn {
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid var(--glass-border);
    color: var(--text-primary);
    width: 40px;
    height: 40px;
    border-radius: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1rem;
    transition: all 0.2s ease;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.theme-toggle:hover, .search-toggle:hover, .logout-btn:hover {
    background: rgba(255, 59, 111, 0.2);
    border-color: rgba(255, 59, 111, 0.3);
    transform: translateY(-2px);
}

.logout-btn {
    background: rgba(255, 140, 66, 0.15);
    color: #ff8c42;
}

/* ===== FLOATING THEME TOGGLE ===== */
.floating-theme-toggle {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 55px;
    height: 55px;
    border-radius: 55px;
    background: var(--accent-gradient);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.4rem;
    z-index: 9999;
    box-shadow: 0 8px 25px rgba(30, 58, 138, 0.4); 
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 2px solid rgba(255, 255, 255, 0.2);
}

.floating-theme-toggle:hover {
    transform: scale(1.1) rotate(180deg);
    box-shadow: 0 15px 35px rgba(30, 58, 138, 0.6); 
}

/* ===== SEARCH OVERLAY ===== */
#searchOverlay {
    position: fixed;
    top: 70px;
    left: 0;
    width: 100%;
    background: rgba(18, 18, 26, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 15px 20px;
    border-bottom: 1px solid var(--glass-border);
    box-shadow: 0 10px 30px var(--shadow-color);
    z-index: 998;
    display: none;
}

#searchOverlay.show {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

#searchOverlay input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--glass-border);
    border-radius: 50px;
    background: rgba(0, 0, 0, 0.3);
    color: var(--text-primary);
    font-size: 1rem;
    transition: all 0.3s ease;
}

#searchOverlay input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: var(--accent-glow);
}

/* ===== CONTAINERS ===== */
.home-container, .seats-container, .promos-container, .ticket-container, .bookings-container, .payment-container {
    display: none;
    min-height: 100vh;
    width: 100%;
    overflow-y: auto;
    padding: 90px 16px 90px 16px;
    text-align: center;
    position: relative;
    background: var(--bg-primary);
}

/* Auth screens */
#login, #signup {
    overflow: hidden;
    height: 100vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding: 20px;
    background: linear-gradient(135deg, #0a0a0f 0%, #1a1a2a 100%);
    position: relative;
}

#login::before, #signup::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><circle cx="30" cy="30" r="2" fill="rgba(255,59,111,0.1)"/></svg>');
    opacity: 0.5;
    pointer-events: none;
}

/* ===== AUTH LOGO ===== */
.auth-logo-container {
    text-align: center;
    margin-bottom: 30px;
    animation: floatIn 0.8s ease;
    position: relative;
    z-index: 2;
}

@keyframes floatIn {
    0% {
        opacity: 0;
        transform: translateY(-30px) scale(0.9);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.auth-logo {
    width: 130px;
    height: 130px;
    object-fit: contain;
    border-radius: 40px;
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3); 
    border: 3px solid rgba(255, 255, 255, 0.1);
    padding: 5px;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.auth-logo-text {
    font-size: 2.5rem;
    font-weight: 800;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-top: 15px;
    letter-spacing: -1px;
    text-shadow: 0 10px 20px rgba(30, 58, 138, 0.3); 
}

/* ===== AUTH CARDS ===== */
.auth-card {
    background: rgba(18, 18, 26, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    padding: 40px 30px;
    width: 100%;
    max-width: 360px;
    margin: 0 auto;
    border-radius: 40px;
    box-shadow: 0 30px 60px var(--shadow-color);
    border: 1px solid var(--glass-border);
    animation: slideUp 0.8s ease;
    position: relative;
    z-index: 2;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-card h2 {
    margin-bottom: 20px;
    color: var(--text-primary);
    font-size: 2rem;
    font-weight: 700;
}

.auth-card input {
    width: 100%;
    padding: 16px 20px;
    margin: 8px 0;
    border: 2px solid var(--glass-border);
    border-radius: 50px;
    font-size: 1rem;
    background: rgba(0, 0, 0, 0.2);
    color: var(--text-primary);
    transition: all 0.3s ease;
}

.auth-card input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: var(--accent-glow);
}

.auth-card button {
    width: 100%;
    padding: 16px;
    margin-top: 20px;
    border: none;
    border-radius: 50px;
    background: var(--accent-gradient);
    color: #fff;
    font-weight: 700;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3); 
}

.auth-card button:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(30, 58, 138, 0.4); 
}

.error-msg {
    color: #ff8c42; 
    margin: 10px 0;
    background: rgba(255, 140, 66, 0.1); 
    padding: 12px;
    border-radius: 30px;
    font-size: 0.9rem;
    border: 1px solid rgba(255, 140, 66, 0.3); 
}

/* ===== SCREEN HEADER ===== */
.screen-header {
    text-align: center;
    margin-bottom: 30px;
    padding: 35px 20px;
    background: linear-gradient(135deg, #1e3a8a, #ff8c42); 
    border-radius: 40px;
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3); 
    color: white;
    position: relative;
    overflow: hidden;
}

.screen-header::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: rotate 20s linear infinite;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

.screen-header h1 {
    font-size: 2.2rem;
    margin-bottom: 10px;
    font-weight: 800;
    letter-spacing: -1px;
    position: relative;
    z-index: 2;
}

.screen-header p {
    font-size: 1rem;
    opacity: 0.9;
    position: relative;
    z-index: 2;
}

/* ===== TAB BUTTONS ===== */
.tab-buttons {
    display: flex;
    gap: 10px;
    margin: 0 auto 30px auto;
    width: fit-content;
    background: var(--bg-secondary);
    border-radius: 60px;
    padding: 6px;
    border: 1px solid var(--glass-border);
    position: relative;
    z-index: 10;
    box-shadow: var(--shadow-elevation);
}

.tab-buttons button {
    padding: 12px 30px;
    background: transparent;
    color: var(--text-secondary);
    font-weight: 600;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    border-radius: 60px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    white-space: nowrap;
}

.tab-buttons button.active {
    background: var(--accent-gradient);
    color: white;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3); 
    transform: translateY(-2px);
}

/* ===== MOVIES GRID ===== */
.movies-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
    margin-top: 20px;
}

.movie-card {
    background: var(--bg-card);
    border-radius: 30px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: left;
    border: 1px solid var(--glass-border);
    position: relative;
    padding-bottom: 12px;
    box-shadow: var(--shadow-elevation);
}

.movie-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2);
    border-color: rgba(255, 140, 66, 0.3); 
}

.movie-card:active {
    transform: scale(0.98);
}

.movie-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    border-bottom: 1px solid var(--glass-border);
    transition: transform 0.5s ease;
}

.movie-card:hover img {
    transform: scale(1.1);
}

.movie-card h3 {
    margin: 12px 12px 4px 12px;
    font-size: 1rem;
    font-weight: 700;
    line-height: 1.3;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.movie-card p {
    color: var(--text-secondary);
    font-size: 0.8rem;
    margin: 0 12px 4px 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.movie-card .price {
    color: var(--accent-yellow);
    font-weight: 700;
    font-size: 1rem;
    margin: 4px 12px 0 12px;
}

.rating-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.7rem;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}

.rating-badge.g { background: var(--rating-g); }
.rating-badge.pg { background: var(--rating-pg); }
.rating-badge.pg13 { background: var(--rating-pg13); color: #333; }
.rating-badge.r { background: var(--rating-r); }
.rating-badge.nc17 { background: var(--rating-nc17); }

.coming-soon-badge {
    position: absolute;
    top: 12px;
    left: 12px;
    background: var(--accent-gradient);
    color: white;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 4px 10px rgba(30, 58, 138, 0.3);
}

/* ===== MOVIE DETAILS ===== */
.movie-header-trailer {
    position: relative;
    width: 100%;
    height: 500px;
    border-radius: 0 0 40px 40px;
    overflow: hidden;
    margin-bottom: 0;
    box-shadow: 0 30px 60px var(--shadow-color);
}

.trailer-container {
    position: relative;
    width: 100%;
    height: 100%;
    cursor: pointer;
}

.trailer-poster {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 8s ease;
}

.trailer-container:hover .trailer-poster {
    transform: scale(1.1);
}

.play-button-large {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2.2rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    z-index: 10;
    border: 2px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.play-button-large i {
    margin-left: 5px;
}

.trailer-container:hover .play-button-large {
    transform: translate(-50%, -50%) scale(1.1);
    background: rgba(255, 59, 111, 0.5);
    border-color: rgba(255, 255, 255, 0.8);
    box-shadow: 0 0 40px rgba(255, 59, 111, 0.5);
}

.trailer-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, 
        transparent 0%,
        rgba(0,0,0,0.4) 50%,
        rgba(0,0,0,0.9) 100%);
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    padding: 40px 30px 50px;
    z-index: 5;
    pointer-events: none;
}

.movie-header-title {
    font-size: 3rem;
    font-weight: 800;
    color: white;
    margin-bottom: 10px;
    text-shadow: 2px 2px 20px rgba(0,0,0,0.5);
    line-height: 1.1;
    letter-spacing: -1px;
}

.movie-header-subtitle {
    font-size: 1.2rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 15px;
    font-style: italic;
    text-shadow: 1px 1px 10px rgba(0,0,0,0.5);
}

.movie-header-meta {
    display: flex;
    gap: 20px;
    font-size: 1rem;
    color: rgba(255,255,255,0.9);
    margin-bottom: 15px;
    text-shadow: 1px 1px 5px rgba(0,0,0,0.5);
}

.movie-header-meta span {
    display: flex;
    align-items: center;
    gap: 8px;
}

.movie-header-meta i {
    color: var(--accent-yellow);
    filter: drop-shadow(0 2px 5px rgba(255,209,102,0.5));
}

.movie-header-rating {
    display: inline-block;
    padding: 8px 20px;
    border-radius: 40px;
    font-weight: 700;
    font-size: 1rem;
    color: white;
    width: fit-content;
    margin-bottom: 20px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    letter-spacing: 0.5px;
}

.movie-content-section {
    background: var(--bg-secondary);
    border-radius: 40px 40px 0 0;
    margin-top: -30px;
    padding: 35px 25px;
    position: relative;
    z-index: 10;
}

.movie-description {
    font-size: 1rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0 0 30px 0;
    padding: 0;
}

.movie-detail-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    margin: 0 0 30px 0;
    padding: 25px 0;
    border-top: 1px solid var(--glass-border);
    border-bottom: 1px solid var(--glass-border);
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.detail-label {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.detail-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.cast-mini-section {
    margin: 0 0 30px 0;
}

.cast-mini-title {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-primary);
}

.cast-mini-list {
    display: flex;
    gap: 20px;
    overflow-x: auto;
    padding: 0 0 15px 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--accent-primary) var(--bg-tertiary);
}

.cast-mini-list::-webkit-scrollbar {
    height: 4px;
}

.cast-mini-list::-webkit-scrollbar-track {
    background: var(--bg-tertiary);
    border-radius: 10px;
}

.cast-mini-list::-webkit-scrollbar-thumb {
    background: var(--accent-primary);
    border-radius: 10px;
}

.cast-mini-item {
    min-width: 100px;
    text-align: center;
}

.cast-mini-avatar {
    width: 80px;
    height: 80px;
    border-radius: 40px;
    background: var(--accent-gradient);
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    color: white;
    font-weight: 700;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3); 
}

.cast-mini-name {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-primary);
}

.cast-mini-character {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.pricing-tag {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;
    margin: 0 0 30px 0;
    border-top: 1px solid var(--glass-border);
    border-bottom: 1px solid var(--glass-border);
}

.pricing-tag .price {
    font-size: 2rem;
    font-weight: 800;
    color: var(--accent-yellow);
    letter-spacing: -1px;
}

.pricing-tag .points {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--accent-green);
    font-size: 1rem;
    font-weight: 600;
    background: rgba(6, 214, 160, 0.1);
    padding: 8px 16px;
    border-radius: 40px;
}

/* ===== DATE & TIME SELECTOR ===== */
.selector-label {
    font-size: 1.2rem;
    font-weight: 700;
    margin-bottom: 20px;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 10px;
}

.selector-label i {
    color: var(--accent-primary);
    font-size: 1.3rem;
}

.dates-scroll {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 5px 0 20px 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--accent-primary) var(--bg-tertiary);
}

.dates-scroll::-webkit-scrollbar {
    height: 4px;
}

.dates-scroll::-webkit-scrollbar-track {
    background: var(--bg-tertiary);
    border-radius: 10px;
}

.dates-scroll::-webkit-scrollbar-thumb {
    background: var(--accent-primary);
    border-radius: 10px;
}

.date-card {
    min-width: 100px;
    padding: 20px 10px;
    background: var(--bg-card);
    border: 1px solid var(--glass-border);
    border-radius: 30px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    box-shadow: var(--shadow-elevation);
}

.date-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(255, 59, 111, 0.2);
    border-color: var(--accent-primary);
}

.date-card .day {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.date-card .date-num {
    font-size: 2.2rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1;
}

.date-card .month {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.date-card.active {
    background: var(--accent-gradient);
    border-color: transparent;
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.4); 
}

.date-card.active .day,
.date-card.active .date-num,
.date-card.active .month {
    color: white;
}

.time-grid {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    padding: 5px 0 20px 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: var(--accent-primary) var(--bg-tertiary);
}

.time-grid::-webkit-scrollbar {
    height: 4px;
}

.time-card {
    min-width: 110px;
    padding: 16px 20px;
    background: var(--bg-card);
    border: 1px solid var(--glass-border);
    border-radius: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    box-shadow: var(--shadow-elevation);
}

.time-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(255, 59, 111, 0.2);
    border-color: var(--accent-primary);
}

.time-card .time-value {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}

.time-card .time-period {
    font-size: 0.8rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.time-card.active {
    background: var(--accent-gradient);
    border-color: transparent;
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.4); 
}

.time-card.active .time-value,
.time-card.active .time-period {
    color: white;
}

.book-tickets-btn {
    background: var(--accent-gradient);
    color: white;
    border: none;
    padding: 20px;
    border-radius: 60px;
    font-size: 1.2rem;
    font-weight: 700;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    cursor: pointer;
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3); 
    margin-top: 30px;
    transition: all 0.3s ease;
}

.book-tickets-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px rgba(30, 58, 138, 0.4); 
}

.book-tickets-btn i {
    font-size: 1.3rem;
}

/* ===== SEAT LAYOUT ===== */
.seat-layout-container {
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    margin: 20px -16px;
    padding: 10px 16px;
    scrollbar-width: thin;
    scrollbar-color: var(--accent-primary) var(--bg-tertiary);
    touch-action: pan-x pan-y pinch-zoom; 
    cursor: grab;
    user-select: none;
    -webkit-user-select: none;
}

.seat-layout-container:active {
    cursor: grabbing;
}

.seat-layout {
    min-width: max-content;
    padding: 10px 0;
    display: flex;
    flex-direction: column;
    gap: 5px;
    transform-origin: top left;
    transition: transform 0.1s ease; 
}

/* Zoom controls */
.zoom-controls {
    position: sticky;
    bottom: 100px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    background: var(--bg-card);
    padding: 8px 16px;
    border-radius: 40px;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
    z-index: 100;
    width: fit-content;
    margin: 0 auto 20px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.zoom-btn {
    width: 40px;
    height: 40px;
    border-radius: 40px;
    background: var(--accent-gradient);
    color: white;
    border: none;
    font-size: 1.2rem;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: 0 4px 10px rgba(30, 58, 138, 0.3);
    -webkit-appearance: none;
    appearance: none;
}

.zoom-btn:active {
    transform: scale(0.95);
}

.zoom-btn:focus {
    outline: none;
}

/* Light mode adjustments */
[data-theme="light"] .zoom-controls {
    background: rgba(255, 255, 255, 0.95);
    border-color: var(--glass-border);
}

[data-theme="light"] .zoom-level {
    color: var(--text-primary);
}

.seat-layout-container {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    margin: 20px -16px;
    padding: 10px 16px;
    scrollbar-width: thin;
    scrollbar-color: var(--accent-primary) var(--bg-tertiary);
}

.seat-layout {
    min-width: max-content;
    padding: 10px 0;
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.seat-row-container {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin: 2px 0;
    gap: 5px;
}

.row-label-left, .row-label-right {
    width: 25px;
    text-align: center;
    font-weight: 800;
    font-size: 0.9rem;
    color: var(--accent-yellow);
    flex-shrink: 0;
}

.seat-row {
    display: flex;
    gap: 3px;
    align-items: center;
    flex-wrap: nowrap;
}

.seat {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--seat-available);
    text-align: center;
    line-height: 28px;
    font-weight: 700;
    cursor: pointer;
    font-size: 0.7rem;
    border: 2px solid transparent;
    color: var(--text-primary);
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
}

.seat:hover {
    transform: scale(1.1);
    border-color: var(--accent-primary);
    box-shadow: 0 5px 15px rgba(30, 58, 138, 0.3); 
}

.seat.selected {
    background: var(--seat-selected);
    color: white;
    border-color: white;
    box-shadow: 0 0 20px rgba(255, 142, 83, 0.5);
}

.seat.occupied {
    background: var(--seat-occupied);
    cursor: not-allowed;
    opacity: 0.5;
    color: white;
}

.seat.occupied:hover {
    transform: none;
    border-color: transparent;
    box-shadow: none;
}

.screen {
    width: 100%;
    margin: 20px 0;
    background: linear-gradient(180deg, #2a2a3a, #1a1a2a);
    color: var(--accent-yellow);
    text-align: center;
    padding: 12px 0;
    border-radius: 30px;
    font-weight: 700;
    font-size: 1rem;
    letter-spacing: 5px;
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    border: 1px solid var(--glass-border);
    text-transform: uppercase;
}

.seat-legend {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin: 25px 0;
    padding: 15px;
    background: var(--bg-card);
    border-radius: 60px;
    border: 1px solid var(--glass-border);
    flex-wrap: wrap;
}

.seat-legend span {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.seat-legend .seat {
    width: 20px;
    height: 20px;
    line-height: 20px;
    font-size: 0;
    pointer-events: none;
}

.zoom-controls {
    position: sticky;
    bottom: 100px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    background: var(--bg-card);
    padding: 8px 16px;
    border-radius: 40px;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
    z-index: 1000; 
    width: fit-content;
    margin: 0 auto 20px;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    pointer-events: auto; 
}

/* Ensure seat container doesn't block zoom controls */
.seat-layout-container {
    position: relative;
    z-index: 1;
}

/* ===== PAYMENT STYLES ===== */
.payment-methods {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin: 20px 0;
}

.payment-method {
    background: var(--bg-card);
    border-radius: 30px;
    padding: 25px 15px;
    text-align: center;
    cursor: pointer;
    border: 2px solid transparent;
    border: 1px solid var(--glass-border);
    transition: all 0.3s ease;
    box-shadow: var(--shadow-elevation);
}

.payment-method:hover {
    transform: translateY(-5px);
    border-color: var(--accent-primary);
    box-shadow: 0 15px 30px rgba(30, 58, 138, 0.2); 
}

.payment-method.selected {
    border-color: var(--accent-primary);
    background: rgba(255, 140, 66, 0.1);
    transform: translateY(-5px);
}

.payment-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
    color: var(--accent-primary);
}

.payment-method h3 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 5px;
}

.payment-method p {
    font-size: 0.8rem;
    color: var(--text-secondary);
}

.payment-form {
    background: var(--bg-card);
    border-radius: 40px;
    padding: 30px;
    margin-top: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
}

.payment-form h3 {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.form-group {
    margin-bottom: 20px;
    text-align: left;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-secondary);
}

.form-group input {
    width: 100%;
    padding: 16px 20px;
    border: 2px solid var(--glass-border);
    border-radius: 30px;
    background: rgba(0, 0, 0, 0.2);
    color: var(--text-primary);
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: var(--accent-glow);
}

.payment-summary {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 30px;
    padding: 20px;
    margin: 20px 0;
    border: 1px solid var(--glass-border);
}

.payment-summary h4 {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 15px;
    color: var(--text-primary);
}

.summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    font-size: 0.95rem;
    color: var(--text-secondary);
    padding: 5px 0;
}

.summary-item.total {
    font-weight: 800;
    color: var(--accent-yellow);
    font-size: 1.2rem;
    border-top: 1px solid var(--glass-border);
    padding-top: 15px;
    margin-top: 10px;
}

.pay-now-btn {
    background: var(--accent-gradient);
    color: white;
    border: none;
    padding: 18px;
    border-radius: 60px;
    cursor: pointer;
    font-size: 1.2rem;
    font-weight: 700;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.3); 
}

.pay-now-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 25px 50px rgba(30, 58, 138, 0.4);
}

/* ===== TICKET STYLES ===== */
.ticket-card {
    background: var(--bg-card);
    border-radius: 40px;
    overflow: hidden;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
}

.ticket-body {
    padding: 30px;
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.ticket-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    padding: 10px 0;
    border-bottom: 1px dashed var(--glass-border);
}

.info-label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--text-primary);
}

.ticket-qr {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 20px;
    padding: 25px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 30px;
    border: 1px solid var(--glass-border);
}

.qr-code {
    width: 180px;
    height: 180px;
    background: white;
    border-radius: 20px;
    padding: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    box-shadow: 0 10px 20px rgba(0,0,0,0.2);
}

.reference-number {
    margin-top: 10px;
    padding: 12px 20px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 40px;
    text-align: center;
    width: 100%;
}

.reference-label {
    font-size: 0.7rem;
    color: var(--text-secondary);
    margin-bottom: 5px;
}

.reference-value {
    font-size: 1rem;
    font-weight: 800;
    color: var(--accent-yellow);
    word-break: break-all;
    font-family: 'Space Grotesk', monospace;
}

.ticket-actions {
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
    border-top: 1px solid var(--glass-border);
}

.action-btn {
    padding: 12px 25px;
    border: none;
    border-radius: 40px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
}

.action-btn.btn-primary {
    background: var(--accent-gradient);
    color: white;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3); 
}

.action-btn.btn-secondary {
    background: rgba(255, 255, 255, 0.1);
    color: var(--text-primary);
    border: 1px solid var(--glass-border);
}

.action-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
}

/* ===== PROMOS STYLES ===== */
.promos-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    margin-bottom: 40px;
}

.promo-card {
    background: var(--bg-card);
    border-radius: 40px;
    padding: 30px;
    box-shadow: var(--shadow-elevation);
    border: 1px solid var(--glass-border);
    position: relative;
    transition: all 0.3s ease;
}

.promo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 30px 60px rgba(30, 58, 138, 0.2);
    border-color: rgba(255, 140, 66, 0.3);
}

.promo-badge {
    position: absolute;
    top: 20px;
    right: 20px;
    background: var(--accent-gradient);
    color: white;
    padding: 6px 12px;
    border-radius: 30px;
    font-size: 0.7rem;
    font-weight: 700;
    box-shadow: 0 5px 10px rgba(30, 58, 138, 0.3);
}

.promo-icon {
    font-size: 3rem;
    color: var(--accent-primary);
    margin-bottom: 15px;
}

.promo-title {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-primary);
}

.promo-description {
    color: var(--text-secondary);
    margin-bottom: 20px;
    font-size: 0.95rem;
    line-height: 1.5;
}

.promo-cost {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
    border-top: 1px solid var(--glass-border);
}

.points-required {
    font-weight: 800;
    color: var(--accent-yellow);
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.redeem-btn {
    background: var(--accent-gradient);
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 40px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: 0 10px 20px rgba(30, 58, 138, 0.3);
}

.redeem-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 15px 30px rgba(30, 58, 138, 0.4); 
}

.redeem-btn:disabled {
    background: var(--bg-tertiary);
    cursor: not-allowed;
    opacity: 0.5;
    box-shadow: none;
}

.redeemed-badge {
    background: var(--accent-green);
    color: white;
    padding: 12px 25px;
    border-radius: 40px;
    font-size: 1rem;
    font-weight: 700;
}

.rewards-history {
    background: var(--bg-card);
    border-radius: 40px;
    padding: 30px;
    border: 1px solid var(--glass-border);
    margin-top: 20px;
    box-shadow: var(--shadow-elevation);
}

.rewards-history h3 {
    font-size: 1.4rem;
    font-weight: 700;
    margin-bottom: 25px;
    text-align: center;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.rewards-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.reward-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 30px;
    border-left: 4px solid var(--accent-primary);
    flex-wrap: wrap;
    gap: 15px;
    transition: all 0.3s ease;
}

.reward-item:hover {
    transform: translateX(5px);
    background: rgba(255, 255, 255, 0.1);
}

.reward-info {
    flex: 1;
    min-width: 150px;
}

.reward-title {
    font-weight: 700;
    font-size: 1rem;
    color: var(--text-primary);
    margin-bottom: 5px;
}

.reward-date {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.reward-cost {
    font-weight: 800;
    color: var(--accent-primary);
    font-size: 1rem;
    background: rgba(255, 140, 66, 0.1); 
    padding: 6px 12px;
    border-radius: 30px;
    white-space: nowrap;
}

.save-qr-btn {
    background: var(--accent-green);
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 30px;
    cursor: pointer;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
    transition: all 0.3s ease;
}

.save-qr-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
}

/* ===== BOOKINGS STYLES ===== */
.booking-card {
    background: var(--bg-card);
    border-radius: 40px;
    padding: 25px;
    margin-bottom: 20px;
    border: 1px solid var(--glass-border);
    box-shadow: var(--shadow-elevation);
    transition: all 0.3s ease;
}

.booking-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 20px 40px rgba(30, 58, 138, 0.2); 
    border-color: rgba(255, 140, 66, 0.3); 
}

.booking-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid var(--glass-border);
    flex-wrap: wrap;
    gap: 10px;
}

.booking-movie {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-primary);
}

.booking-date {
    font-size: 0.8rem;
    color: var(--text-secondary);
    background: rgba(255, 255, 255, 0.05);
    padding: 5px 12px;
    border-radius: 30px;
}

.booking-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
    margin-bottom: 20px;
}

.booking-detail {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.7rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}

.detail-value {
    font-size: 0.95rem;
    font-weight: 600;
    color: var(--text-primary);
}

.booking-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.action-btn-small {
    padding: 8px 20px;
    border: none;
    border-radius: 30px;
    font-weight: 600;
    cursor: pointer;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s ease;
}

.action-btn-small:hover {
    transform: translateY(-2px);
}

/* ===== FAQ STYLES ===== */
.home-faq-section {
    margin: 40px 0 20px;
}

.home-faq-section h2 {
    font-size: 1.8rem;
    font-weight: 800;
    margin-bottom: 25px;
    text-align: center;
    background: var(--accent-gradient);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.home-important-notice {
    background: rgba(255, 140, 66, 0.1); 
    border-left: 4px solid var(--accent-primary);
    padding: 20px;
    border-radius: 30px;
    margin-bottom: 25px;
    text-align: center;
    border: 1px solid rgba(255, 140, 66, 0.3); 
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}

.home-important-notice h3 {
    font-size: 1.2rem;
    margin-bottom: 8px;
    color: var(--accent-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.home-faq-item {
    background: var(--bg-card);
    border-radius: 30px;
    margin-bottom: 12px;
    overflow: hidden;
    border: 1px solid var(--glass-border);
    transition: all 0.3s ease;
}

.home-faq-question {
    padding: 20px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-primary);
    background: rgba(255, 255, 255, 0.03);
}

.home-faq-question i {
    transition: transform 0.3s ease;
    color: var(--accent-primary);
}

.home-faq-answer {
    padding: 0 20px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    color: var(--text-secondary);
    font-size: 0.9rem;
    line-height: 1.6;
}

.home-faq-item.active .home-faq-question i {
    transform: rotate(180deg);
}

.home-faq-item.active .home-faq-answer {
    padding: 20px;
    max-height: 200px;
    background: rgba(255, 255, 255, 0.02);
}

/* ===== MODALS ===== */
#movieDetailsModal, #rewardTicketModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    z-index: 2000;
    justify-content: center;
    align-items: center;
    padding: 20px;
}

.modal-content, .reward-ticket-content {
    background: var(--bg-card);
    width: 100%;
    max-width: 400px;
    max-height: 80vh;
    overflow-y: auto;
    padding: 30px;
    border-radius: 40px;
    position: relative;
    border: 1px solid var(--glass-border);
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.4);
}

#closeModal, #closeRewardTicket {
    position: absolute;
    top: 20px;
    right: 20px;
    cursor: pointer;
    font-size: 1.3rem;
    color: var(--accent-primary);
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 40px;
    background: rgba(255, 140, 66, 0.1); 
    border: 1px solid rgba(255, 140, 66, 0.3); 
    transition: all 0.3s ease;
}

#closeModal:hover, #closeRewardTicket:hover {
    background: var(--accent-primary);
    color: white;
    transform: rotate(90deg);
}

/* ===== TRAILER MODAL ===== */
#trailerModal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.95);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    z-index: 10000;
    justify-content: center;
    align-items: center;
    padding: 20px;
    cursor: pointer;
}

.trailer-modal-content {
    width: 100%;
    max-width: 900px;
    position: relative;
    border-radius: 40px;
    overflow: hidden;
    background: #000;
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5);
    cursor: default;
}

.trailer-video-container {
    position: relative;
    width: 100%;
    padding-top: 56.25%;
    background: #000;
}

.trailer-video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.close-trailer {
    position: absolute;
    top: -50px;
    right: 0;
    width: 50px;
    height: 50px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.4rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10001;
    border: 2px solid rgba(255, 255, 255, 0.3);
}

.close-trailer:hover {
    background: var(--accent-primary);
    transform: scale(1.1);
}

/* ===== TOAST ===== */
.toast {
    position: fixed;
    bottom: 30px;
    left: 20px;
    right: 20px;
    background: rgba(18, 18, 26, 0.9);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    color: var(--text-primary);
    padding: 16px 20px;
    border-radius: 60px;
    box-shadow: 0 20px 40px var(--shadow-color);
    z-index: 10000;
    display: flex;
    align-items: center;
    gap: 12px;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid var(--accent-primary);
    font-size: 1rem;
    max-width: 400px;
    margin: 0 auto;
}

.toast.show {
    transform: translateY(0);
    opacity: 1;
}

.toast.success {
    border-left-color: var(--accent-green);
}

.toast.error {
    border-left-color: #ff3b6f;
}

/* ===== BACK BUTTON ===== */
.back-btn {
    position: absolute;
    top: 20px;
    left: 20px;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(10px);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 40px;
    cursor: pointer;
    font-weight: 600;
    font-size: 1rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    z-index: 1000;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
    transition: all 0.3s ease;
}

.back-btn:hover {
    background: var(--accent-primary);
    transform: translateX(-5px);
}

/* ===== LOADING ===== */
.loading {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: var(--accent-primary);
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.payment-processing .processing-spinner {
    width: 60px;
    height: 60px;
    border: 4px solid rgba(255, 255, 255, 0.1);
    border-top: 4px solid var(--accent-primary);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

/* ===== NO RESULTS ===== */
.no-results, .no-bookings, .no-rewards {
    grid-column: 1 / -1;
    padding: 60px 20px;
    background: var(--bg-card);
    border-radius: 40px;
    border: 1px solid var(--glass-border);
    text-align: center;
}

.no-results i, .no-bookings i, .no-rewards i {
    font-size: 4rem;
    margin-bottom: 20px;
    color: var(--accent-primary);
    opacity: 0.5;
}

.no-results h3, .no-bookings h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 10px;
    color: var(--text-primary);
}

.no-results p, .no-bookings p {
    font-size: 1rem;
    color: var(--text-secondary);
    margin-bottom: 20px;
}

/* ===== PRINT STYLES ===== */
@media print {
    body * { visibility: hidden; }
    .ticket-container, .ticket-container * { visibility: visible; }
    .ticket-container {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white;
        color: black;
    }
    .ticket-actions { display: none; }
}

/* ===== RESPONSIVE ADJUSTMENTS ===== */
@media (max-width: 380px) {
    .movie-header-title { font-size: 2.2rem; }
    .movie-header-poster { height: 400px; }
    .date-card { min-width: 85px; padding: 15px 5px; }
    .date-card .date-num { font-size: 1.8rem; }
    .time-card { min-width: 95px; padding: 12px 15px; }
    .seat { width: 24px; height: 24px; line-height: 24px; font-size: 0.65rem; }
    .seat-layout { min-width: 600px; }
}

/* Ensure navbar stays hidden on movie details screen even when navigating back */
body.movie-details-active .navbar,
body.seats-active .navbar,
body.payment-active .navbar,
body.ticket-active .navbar {
    display: none !important;
}

/* Ensure floating theme toggle stays hidden on these screens */
body.movie-details-active .floating-theme-toggle,
body.seats-active .floating-theme-toggle,
body.payment-active .floating-theme-toggle,
body.ticket-active .floating-theme-toggle {
    display: none !important;
}

/* Ensure proper padding when navbar is hidden */
body.movie-details-active .home-container,
body.movie-details-active .seats-container,
body.movie-details-active .promos-container,
body.movie-details-active .ticket-container,
body.movie-details-active .bookings-container,
body.movie-details-active .payment-container,
body.seats-active .seats-container,
body.payment-active .payment-container,
body.ticket-active .ticket-container {
    padding-top: 20px !important;
}

/* Fix for movie details screen */
body.movie-details-active #movieDetails {
    padding-top: 0 !important;
}

/* Hide bottom navigation on login and signup screens */
body.auth-screen .bottom-nav {
    display: none !important;
}

/* Adjust container padding for auth screens */
body.auth-screen .home-container {
    padding-bottom: 20px !important;
}
</style>
</head>
<body>

<!-- Global Variables -->
<script>
// Global variables
let userPoints = <?php echo isset($userPoints) ? $userPoints : 150; ?>;
let isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
let currentMovie = null;
let currentPrice = 0;
let currentMovieId = null;
let selectedDate = null;
let selectedTime = null;
let selectedPaymentMethod = null;
let mobileMenuOpen = false;
let searchOverlayOpen = false;
let currentMovieTrailer = null;

// Empty arrays - data will be loaded from database
let allMovies = [];
let allComingSoonMovies = [];
let allMoviesComplete = [];
let promos = [];
let userRedeemedPromos = {};
let userBookings = [];
let occupiedSeatsData = {};

// Movie trailer data (keep this as is)
const movieTrailers = [
    {
        id: 1,
        title: "Send Help",
        keywords: ["send", "help", "send help"],
        thumbnail: "https://img.youtube.com/vi/R4wiXj9NmEE/maxresdefault.jpg",
        videoUrl: "R4wiXj9NmEE"
    },
    {
        id: 2,
        title: "Superman",
        keywords: ["superman", "super man"],
        thumbnail: "https://img.youtube.com/vi/3DluCevVcuQ/maxresdefault.jpg",
        videoUrl: "3DluCevVcuQ"
    },
    {
        id: 3,
        title: "Wolf Man",
        keywords: ["wolf", "wolf man", "wolfman"],
        thumbnail: "https://img.youtube.com/vi/kAw4PH2IQgo/maxresdefault.jpg",
        videoUrl: "kAw4PH2IQgo"
    },
    {
        id: 4,
        title: "Goat",
        keywords: ["goat"],
        thumbnail: "https://img.youtube.com/vi/ggZA2oi8S5s/maxresdefault.jpg",
        videoUrl: "ggZA2oi8S5s"
    },
    {
        id: 5,
        title: "Avengers: Doomsday",
        keywords: ["avengers", "doomsday", "avengers doomsday"],
        thumbnail: "https://img.youtube.com/vi/399Ez7WHK5s/maxresdefault.jpg",
        videoUrl: "399Ez7WHK5s"
    },
    {
        id: 6,
        title: "Scream 7",
        keywords: ["scream", "scream 7"],
        thumbnail: "https://img.youtube.com/vi/UJrghaPJ0RY/maxresdefault.jpg",
        videoUrl: "UJrghaPJ0RY"
    },
    {
        id: 7,
        title: "Our House",
        keywords: ["our", "house", "our house"],
        thumbnail: "https://img.youtube.com/vi/qyPvIJPmAVA/maxresdefault.jpg",
        videoUrl: "qyPvIJPmAVA"
    },
    {
        id: 8,
        title: "Hoppers",
        keywords: ["hoppers"],
        thumbnail: "https://img.youtube.com/vi/PypDSyIRRSs/hqdefault.jpg",
        videoUrl: "PypDSyIRRSs"
    },
    {
        id: 9,
        title: "Crime 101",
        keywords: ["crime", "crime 101"],
        thumbnail: "https://img.youtube.com/vi/f5y-cziwmMw/maxresdefault.jpg",
        videoUrl: "f5y-cziwmMw"
    },
    {
        id: 10,
        title: "The Loved One",
        keywords: ["loved", "the loved one"],
        thumbnail: "https://img.youtube.com/vi/dTULh0m9kBQ/maxresdefault.jpg",
        videoUrl: "dTULh0m9kBQ"
    },
    {
        id: 11,
        title: "Whistle",
        keywords: ["whistle"],
        thumbnail: "https://img.youtube.com/vi/eDESvwUcTp8/maxresdefault.jpg",
        videoUrl: "eDESvwUcTp8"
    },
    {
        id: 12,
        title: "Batang Paco",
        keywords: ["batang", "paco", "batang paco"],
        thumbnail: "https://img.youtube.com/vi/mZ8O-LcUyCg/maxresdefault.jpg",
        videoUrl: "mZ8O-LcUyCg"
    },
    {
        id: 13,
        title: "The Lotto Winner",
        keywords: ["lotto", "winner", "lotto winner"],
        thumbnail: "https://img.youtube.com/vi/e5C7iHdYw_o/maxresdefault.jpg",
        videoUrl: "e5C7iHdYw_o"
    },
    {
        id: 14,
        title: "Wuthering Heights",
        keywords: ["wuthering", "heights", "wuthering heights"],
        thumbnail: "https://img.youtube.com/vi/3fLCdIYShEQ/maxresdefault.jpg",
        videoUrl: "3fLCdIYShEQ"
    },
    {
        id: 15,
        title: "Diabolic",
        keywords: ["diabolic"],
        thumbnail: "https://img.youtube.com/vi/8r-rBYUk4ho/maxresdefault.jpg",
        videoUrl: "8r-rBYUk4ho"
    },
    {
        id: 16,
        title: "Epic: Elvis Presley in Concert",
        keywords: ["epic", "elvis", "presley", "elvis presley"],
        thumbnail: "https://img.youtube.com/vi/NmqWusmzp0k/maxresdefault.jpg",
        videoUrl: "NmqWusmzp0k"
    }
];

function getTrailerFromDatabase(movie) {
    console.log('Getting trailer for movie:', movie); // Debug log
    
    if (movie && movie.trailer_url && movie.trailer_url.trim() !== '') {
        let trailerUrl = movie.trailer_url.trim();
        console.log('Found trailer URL in database:', trailerUrl);
        
        // Return the trailer info
        return {
            videoUrl: trailerUrl,
            title: movie.title || 'Movie',
            thumbnail: movie.poster || `https://img.youtube.com/vi/${trailerUrl}/maxresdefault.jpg`
        };
    }
    
    // Fallback to the hardcoded movieTrailers array if no database trailer
    const titleLower = (movie.title || '').toLowerCase();
    const foundTrailer = movieTrailers.find(t => {
        // Check exact match first
        if (t.title && t.title.toLowerCase() === titleLower) {
            return true;
        }
        // Check keywords
        return t.keywords.some(keyword => titleLower.includes(keyword.toLowerCase()));
    });
    
    console.log('Fallback trailer found:', foundTrailer);
    return foundTrailer;
}

// Function to play trailer
function playTrailer(videoId, title) {
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
    
    const iframe = $(`
        <iframe 
            src="${embedUrl}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    `);
    
    $('#trailerVideoContainer').empty().append(iframe);
    $('#trailerModal').css('display', 'flex');
    $('body').css('overflow', 'hidden');
}

function closeTrailerModal() {
    $('#trailerModal').hide();
    $('#trailerVideoContainer').empty();
    $('body').css('overflow', '');
}

function handleModalClick(event) {
    if (event.target.id === 'trailerModal') {
        closeTrailerModal();
    }
}

function playCurrentMovieTrailer() {
    if (currentMovieTrailer) {
        let videoId = currentMovieTrailer.videoUrl;
        
        // If it's a full YouTube URL, extract the ID
        if (videoId.includes('youtube.com/watch?v=')) {
            videoId = videoId.split('v=')[1]?.split('&')[0];
        } else if (videoId.includes('youtu.be/')) {
            videoId = videoId.split('youtu.be/')[1]?.split('?')[0];
        }
        
        if (videoId) {
            playTrailer(videoId, currentMovieTrailer.title);
        } else {
            showToast('Invalid trailer URL', 'error');
        }
    } else {
        showToast('Trailer not available for this movie', 'info');
    }
}
</script>

<!-- MOBILE NAVBAR - Top Bar -->
<div class="navbar">
    <div class="logo">
        <img src="new-logo.jpg" alt="CineBook Logo" class="logo-jpg" onerror="this.src='https://via.placeholder.com/40?text=CB';">
        <span>CineBook</span>
    </div>

    <div class="navbar-right">
        <button class="search-toggle" onclick="toggleSearchOverlay()">
            <i class="fas fa-search"></i>
        </button>
        <div id="pointsDisplay">
            <i class="fas fa-coins"></i> <span id="userPoints"><?php echo isset($userPoints) ? $userPoints : 150; ?></span>
        </div>
        <button class="logout-btn" onclick="logout()">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    </div>
</div>

<!-- Floating Theme Toggle Button -->
<button class="floating-theme-toggle" id="floatingThemeToggle">
    <i class="fas fa-moon"></i>
</button>

<!-- Bottom Navigation Tabs -->
<div class="bottom-nav">
    <div class="bottom-nav-item" id="bottomHome">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </div>
    <div class="bottom-nav-item" id="bottomBrowse">
        <i class="fas fa-compass"></i>
        <span>Browse</span>
    </div>
    <div class="bottom-nav-item" id="bottomBookings">
        <i class="fas fa-ticket-alt"></i>
        <span>Bookings</span>
    </div>
    <div class="bottom-nav-item" id="bottomPromos">
        <i class="fas fa-gift"></i>
        <span>Promos</span>
    </div>
</div>

<!-- SEARCH OVERLAY -->
<div id="searchOverlay">
    <input type="text" id="mobileMovieSearch" placeholder="Search movies or genres..." onkeyup="performMobileSearch()">
</div>

<!-- Toast Notification -->
<div id="toast" class="toast"></div>

<!-- LOGIN SCREEN -->
<div class="home-container" id="login">
    <div class="auth-logo-container">
        <img src="new-logo.jpg" alt="CineBook Logo" class="auth-logo" onerror="this.src='https://via.placeholder.com/130?text=CB';">
        <div class="auth-logo-text">CineBook</div>
    </div>
    
    <div class="auth-card">
        <h2>Welcome!</h2>
        <p style="color:var(--text-secondary);margin-bottom:15px;">Login to your account</p>
        <div id="loginError" class="error-msg" style="display:none;"></div>
        <form onsubmit="handleLogin(event)">
            <input type="text" id="loginUsername" placeholder="Username" required>
            <input type="password" id="loginPassword" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top:15px;color:var(--text-secondary);font-size:0.9rem;">Don't have an account? <a href="#" style="color:var(--accent-primary);font-weight:600;" onclick="showScreen('signup'); return false;">Sign up</a></p>
    </div>
</div>

<!-- SIGNUP SCREEN -->
<div class="home-container" id="signup">
    <div class="auth-logo-container">
        <img src="new-logo.jpg" alt="CineBook Logo" class="auth-logo" onerror="this.src='https://via.placeholder.com/130?text=CB';">
        <div class="auth-logo-text">CineBook</div>
    </div>
    
    <div class="auth-card">
        <h2>Create Account</h2>
        <p style="color:var(--text-secondary);margin-bottom:15px;">Join CineBook today</p>
        <div id="signupError" class="error-msg" style="display:none;"></div>
        <form onsubmit="handleSignup(event)">
            <input type="text" id="signupUsername" placeholder="Username" required>
            <input type="password" id="signupPassword" placeholder="Password" required>
            <div id="passwordRequirements" style="text-align: left; margin: 10px 0; font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 12px; border-radius: 20px;">
                <div id="req-length"><i class="fas fa-times-circle"></i> 12+ characters</div>
                <div id="req-number"><i class="fas fa-times-circle"></i> One number</div>
                <div id="req-special"><i class="fas fa-times-circle"></i> One special char</div>
                <div id="req-uppercase"><i class="fas fa-times-circle"></i> One uppercase</div>
                <div id="req-lowercase"><i class="fas fa-times-circle"></i> One lowercase</div>
            </div>
            <button type="submit">Create Account</button>
        </form>
        <p style="margin-top:15px;color:var(--text-secondary);font-size:0.9rem;">Already have an account? <a href="#" style="color:var(--accent-primary);font-weight:600;" onclick="showScreen('login'); return false;">Login</a></p>
    </div>
</div>

<!-- HOME SCREEN -->
<div class="home-container" id="home">
    <div class="screen-header">
        <h1>Now Showing</h1>
        <p>Discover the latest movies in theaters</p>
    </div>
    
    <div class="tab-buttons">
        <button id="nowShowingBtn" class="active">Now Showing</button>
        <button id="comingSoonBtn">Coming Soon</button>
    </div>
    
    <div class="movies-grid" id="homeMoviesGrid"></div>
    
    <!-- All Movies Button at the bottom -->
    <div style="margin: 30px 0; text-align: center;">
        <button id="allMoviesBtn" style="padding: 15px 40px; background: var(--accent-gradient); border: none; border-radius: 60px; color: white; font-weight: 700; font-size: 1.1rem; cursor: pointer; box-shadow: 0 20px 40px rgba(255, 59, 111, 0.3); transition: all 0.3s ease;">
            <i class="fas fa-film"></i> View All Movies
        </button>
    </div>
    
    <!-- FAQ Section -->
    <div class="home-faq-section">
        <h2>Frequently Asked Questions</h2>
        
        <!-- Important Notice -->
        <div class="home-important-notice">
            <h3>
                <i class="fas fa-exclamation-circle"></i> Important Notice
            </h3>
            <p style="color: var(--text-secondary); font-size: 0.95rem; line-height: 1.5;">
                Please note: All bookings are non-refundable and cannot be canceled.
            </p>
        </div>
        
        <div class="home-faq-list">
            <div class="home-faq-item">
                <div class="home-faq-question">
                    <span>How do I book a movie ticket?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="home-faq-answer">
                    To book a movie ticket, simply navigate to the Movies section, select the movie you want to watch, choose your preferred date and time, select your seats, and complete the payment process. You'll receive a confirmation with your ticket details.
                </div>
            </div>

            <div class="home-faq-item">
                <div class="home-faq-question">
                    <span>Can I cancel or get a refund for my booking?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="home-faq-answer">
                    No, all bookings are final and non-refundable. Once a booking is confirmed and payment is processed, it cannot be canceled, modified, or refunded. Please double-check your selection before completing your booking.
                </div>
            </div>

            <div class="home-faq-item">
                <div class="home-faq-question">
                    <span>How does the points system work?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="home-faq-answer">
                    You earn 10 points for every ticket you book. These points can be redeemed for various rewards in the Promos section, including free popcorn, combo meal discounts, and even free movie tickets. Points are automatically added to your account after each successful booking.
                </div>
            </div>

            <div class="home-faq-item">
                <div class="home-faq-question">
                    <span>What if I arrive late for my movie?</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="home-faq-answer">
                    We recommend arriving at least 15-20 minutes before the showtime. If you arrive late, you may miss part of the movie, and your seats may be given to standby customers if you're more than 15 minutes late. Please note that late arrivals are not eligible for refunds or exchanges.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- COMING SOON SCREEN -->
<div class="home-container" id="comingSoon">
    <div class="screen-header">
        <h1>Coming Soon</h1>
        <p>Upcoming releases</p>
    </div>
    
    <div class="tab-buttons">
        <button id="nowShowingBtn2">Now Showing</button>
        <button id="comingSoonBtn2" class="active">Coming Soon</button>
    </div>
    
    <div class="movies-grid" id="comingSoonGrid"></div>
</div>

<!-- ALL MOVIES SCREEN -->
<div class="home-container" id="allMovies">
    <div class="screen-header">
        <h1>All Movies</h1>
        <p>Browse our complete collection</p>
    </div>
    
    <div style="display: flex; justify-content: flex-start; margin: 15px 0 25px 15px; width: 100%;">
        <button onclick="goBackFromAllMovies(); return false;" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back
        </button>
    </div>
    
    <div class="movies-grid" id="allMoviesGrid"></div>
</div>

<!-- MOVIE DETAILS SCREEN -->
<div class="home-container" id="movieDetails">
    <div class="movie-details-container">
        <!-- Movie Header with Trailer -->
        <div class="movie-header-trailer" id="movieHeaderTrailer">
            <!-- Back button - consistent style -->
            <button onclick="goBackFromMovieDetails(); return false;" class="back-btn">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            
            <div class="trailer-container" id="movieTrailerContainer" onclick="playCurrentMovieTrailer()">
                <img id="detailsPoster" src="" alt="" class="trailer-poster">
                <div class="play-button-large">
                    <i class="fas fa-play"></i>
                </div>
                <div class="trailer-overlay">
                    <h1 id="detailsTitle" class="movie-header-title"></h1>
                    <div id="detailsSubtitle" class="movie-header-subtitle"></div>
                    <div class="movie-header-meta">
                        <span><i class="fas fa-film"></i> <span id="detailsGenre"></span></span>
                        <span><i class="far fa-clock"></i> <span id="detailsDuration"></span></span>
                    </div>
                    <div id="detailsRatingDisplay" class="movie-header-rating"></div>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="movie-content-section">
            <div class="movie-description">
                <span id="detailsDescription"></span>
            </div>

            <div class="movie-detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Release Date</span>
                    <span class="detail-value" id="detailsReleaseDate">Coming Soon</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Director</span>
                    <span class="detail-value" id="detailsDirector">Sam Raimi</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Language</span>
                    <span class="detail-value">English</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Format</span>
                    <span class="detail-value">2D, 3D, IMAX</span>
                </div>
            </div>

            <div class="cast-mini-section">
                <div class="cast-mini-title">Cast</div>
                <div class="cast-mini-list" id="castMiniList"></div>
            </div>

            <div class="pricing-tag">
                <span class="price">₱<span id="detailsPrice"></span></span>
                <span class="points"><i class="fas fa-coins"></i> 10 pts/ticket</span>
            </div>

            <div class="dates-section">
                <div class="selector-label">
                    <i class="fas fa-calendar-alt"></i> Select Date
                </div>
                <div class="dates-scroll" id="datesScroll"></div>
            </div>

            <div class="times-section">
                <div class="selector-label">
                    <i class="fas fa-clock"></i> Select Time
                </div>
                <div class="time-grid" id="timeGrid"></div>
            </div>

            <button class="book-tickets-btn" onclick="proceedToBooking(); return false;">
                <i class="fas fa-ticket-alt"></i> Book Tickets
            </button>
        </div>
    </div>
</div>

<!-- BOOKING SCREEN -->
<div class="seats-container" id="booking">
    <button onclick="goBackFromBooking(); return false;" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    
    <div class="screen-header">
        <h1>Select Seats</h1>
        <p>Choose your preferred seats</p>
    </div>

    <div id="showInfoContainer"></div>

    <form id="bookingForm">
        <div class="seat-layout-container">
            <div class="seat-layout" id="seatGrid"></div>
        </div>
        <div class="screen">SCREEN</div>
        <input type="hidden" name="movie" id="movieInput">
        <input type="hidden" name="seats" id="seatsInput">
        <input type="hidden" name="total" id="totalPrice">
        <input type="hidden" name="points" id="pointsInput">
    </form>

    <div class="seat-legend">
        <span><div class="seat available"></div> Available</span>
        <span><div class="seat selected"></div> Selected</span>
        <span><div class="seat occupied"></div> Booked</span>
    </div>

    <div style="margin:25px 0; padding:20px; background:var(--bg-card); border-radius:30px; border:1px solid var(--glass-border);">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:15px;">
            <div>
                <p style="font-size:0.9rem; color:var(--text-secondary);">Selected Seats: <span id="selectedSeatsCount" style="font-weight:700;">0</span></p>
                <p style="font-size:0.9rem; color:var(--text-secondary);">Total: <span id="displayTotal" style="color:var(--accent-yellow); font-weight:700;">₱0</span></p>
                <p style="font-size:0.9rem; color:var(--text-secondary);">Points: <span id="displayPoints" style="color:var(--accent-green); font-weight:700;">0</span></p>
            </div>
            <button type="button" onclick="proceedToPayment(); return false;" style="padding:12px 30px; background:var(--accent-gradient); border:none; border-radius:40px; color:white; font-weight:700; font-size:1rem; box-shadow:0 10px 20px rgba(255,59,111,0.3);">
                <i class="fas fa-credit-card"></i> Pay
            </button>
        </div>
    </div>
</div>

<!-- PAYMENT SCREEN -->
<div class="payment-container" id="payment">
    <button onclick="goBackFromPayment(); return false;" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back
    </button>
    
    <div class="screen-header">
        <h1>Payment</h1>
        <p>Complete your booking</p>
    </div>

    <div class="payment-methods">
        <div class="payment-method" onclick="selectPaymentMethod('gcash'); return false;">
            <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
            <h3>GCash</h3>
            <p>Pay with GCash</p>
        </div>
        <div class="payment-method" onclick="selectPaymentMethod('paymaya'); return false;">
            <div class="payment-icon"><i class="fas fa-mobile-alt"></i></div>
            <h3>PayMaya</h3>
            <p>Pay with PayMaya</p>
        </div>
    </div>

    <div class="payment-form" id="paymentForm" style="display: none;">
        <h3 id="paymentMethodTitle">GCash Payment</h3>
        
        <div class="gcash-fields">
            <div class="form-group">
                <label>GCash Number</label>
                <input type="text" id="gcashNumber" placeholder="09XX XXX XXXX" maxlength="11">
            </div>
            <div class="form-group">
                <label>Full Name (as in GCash)</label>
                <input type="text" id="gcashName" placeholder="Juan Dela Cruz">
            </div>
        </div>
        
        <div class="paymaya-fields" style="display:none;">
            <div class="form-group">
                <label>PayMaya Number</label>
                <input type="text" id="paymayaNumber" placeholder="09XX XXX XXXX" maxlength="11">
            </div>
            <div class="form-group">
                <label>Full Name (as in PayMaya)</label>
                <input type="text" id="paymayaName" placeholder="Juan Dela Cruz">
            </div>
        </div>

        <div class="payment-summary">
            <h4>Order Summary</h4>
            <div class="summary-item"><span>Movie:</span> <span id="paymentMovie">-</span></div>
            <div class="summary-item"><span>Seats:</span> <span id="paymentSeats">-</span></div>
            <div class="summary-item total"><span>Total:</span> <span id="paymentTotal">₱0</span></div>
        </div>

        <div class="payment-security">
            <i class="fas fa-lock"></i> <span>Secure payment</span>
        </div>

        <button class="pay-now-btn" onclick="processPayment(); return false;">
            <i class="fas fa-lock"></i> Pay Now
        </button>
    </div>

    <div class="payment-processing" id="paymentProcessing" style="display: none;">
        <div class="processing-spinner"></div>
        <h3>Processing Payment</h3>
        <p>Please wait...</p>
    </div>
</div>

<!-- PROMOS SCREEN -->
<div class="promos-container" id="promos">
    <div class="screen-header">
        <h1>Promos & Rewards</h1>
        <p>Redeem your points</p>
    </div>
    
    <div class="promos-grid" id="promosGrid"></div>
    
    <div class="rewards-history">
        <h3>Your Rewards</h3>
        <div class="rewards-list" id="rewardsList"></div>
    </div>
</div>

<!-- MY BOOKINGS SCREEN -->
<div class="bookings-container" id="bookings">
    <div class="screen-header">
        <h1>My Bookings</h1>
        <p>Your booking history</p>
    </div>
    
    <div class="bookings-list" id="bookingsList"></div>
</div>

<!-- TICKET CONFIRMATION SCREEN -->
<div class="ticket-container" id="ticket">
    <div class="screen-header">
        <h1>Booking Confirmed!</h1>
        <p>Your tickets are ready</p>
    </div>
    
    <div class="ticket-card">
        <div class="ticket-body">
            <div class="ticket-info">
                <div class="info-group"><span class="info-label">Movie</span><span class="info-value" id="ticketMovie"></span></div>
                <div class="info-group"><span class="info-label">Date & Time</span><span class="info-value" id="ticketDateTime"></span></div>
                <div class="info-group"><span class="info-label">Seats</span><span class="info-value" id="ticketSeats"></span></div>
                <div class="info-group"><span class="info-label">Total</span><span class="info-value">₱<span id="ticketTotal"></span></span></div>
                <div class="info-group"><span class="info-label">Points Earned</span><span class="info-value" id="ticketPoints"></span></div>
                <div class="info-group"><span class="info-label">Booking Time</span><span class="info-value" id="ticketTime"></span></div>
            </div>
            <div class="ticket-qr">
                <div id="qrCodeContainer" class="qr-code"></div>
                <div class="reference-number">
                    <div class="reference-label">Reference #</div>
                    <div class="reference-value" id="qrReference">Loading...</div>
                </div>
            </div>
        </div>
        <div class="ticket-actions">
            <button class="action-btn btn-primary" onclick="showScreen('home'); return false;"><i class="fas fa-home"></i> Home</button>
            <button class="action-btn btn-secondary" onclick="downloadTicket(); return false;"><i class="fas fa-download"></i> Save</button>
            <button class="action-btn btn-secondary" onclick="printTicket(); return false;"><i class="fas fa-print"></i> Print</button>
        </div>
    </div>
</div>

<!-- MOVIE DETAILS MODAL -->
<div id="movieDetailsModal">
    <div class="modal-content">
        <span id="closeModal">&times;</span>
        <img id="modalPoster" src="" alt="" style="width:100%; border-radius:20px; margin-bottom:15px;">
        <h2 id="modalTitle" style="color:var(--accent-primary); font-size:1.4rem;"></h2>
        <p id="modalDescription" style="color:var(--text-secondary); font-size:0.95rem; line-height:1.5;"></p>
        <p id="modalGenre" style="font-size:0.9rem;"></p>
        <p id="modalDuration" style="font-size:0.9rem;"></p>
        <p id="modalReleaseDate" style="color:var(--accent-yellow); font-size:0.9rem; margin-top:10px;"></p>
        <div id="modalRating" class="movie-rating-badge" style="margin:10px auto; width:fit-content;"></div>
    </div>
</div>

<!-- REWARD TICKET MODAL -->
<div id="rewardTicketModal">
    <div class="reward-ticket-content">
        <span id="closeRewardTicket">&times;</span>
        <div class="reward-ticket-header" style="background: var(--accent-gradient); color:white; padding:20px; text-align:center; border-radius:30px 30px 0 0;">
            <h2 id="rewardTicketTitle" style="font-size:1.4rem;">Reward Ticket</h2>
        </div>
        <div class="reward-ticket-body" style="padding:25px;">
            <div class="reward-ticket-info">
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Reward:</span>
                    <span class="reward-ticket-value" id="ticketRewardName"></span>
                </div>
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Description:</span>
                    <span class="reward-ticket-value" id="ticketRewardDescription"></span>
                </div>
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Points:</span>
                    <span class="reward-ticket-value" id="ticketPointsSpent"></span>
                </div>
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Redeemed:</span>
                    <span class="reward-ticket-value" id="ticketRedeemedDate"></span>
                </div>
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Valid Until:</span>
                    <span class="reward-ticket-value" id="ticketValidUntil"></span>
                </div>
                <div class="reward-ticket-detail" style="display:flex; justify-content:space-between; margin-bottom:12px; font-size:0.95rem;">
                    <span class="reward-ticket-label">Reference:</span>
                    <span class="reward-ticket-value" id="ticketReference"></span>
                </div>
            </div>
            <div class="reward-ticket-note" style="background:rgba(255,209,102,0.1); padding:12px; border-radius:20px; text-align:center; font-size:0.85rem; margin:15px 0;">
                <i class="fas fa-info-circle"></i> Present at cinema counter
            </div>
            <div class="reward-ticket-actions" style="display:flex; gap:10px; justify-content:center;">
                <button class="action-btn btn-primary" onclick="downloadRewardTicket(); return false;"><i class="fas fa-download"></i> Save</button>
                <button class="action-btn btn-primary" onclick="printRewardTicket(); return false;"><i class="fas fa-print"></i> Print</button>
                <button class="action-btn btn-secondary" onclick="closeRewardTicket(); return false;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- TRAILER MODAL -->
<div id="trailerModal" onclick="handleModalClick(event)">
    <div class="trailer-modal-content" onclick="event.stopPropagation()">
        <div class="close-trailer" onclick="closeTrailerModal()">
            <i class="fas fa-times"></i>
        </div>
        <div class="trailer-video-container" id="trailerVideoContainer">
            <!-- YouTube iframe will be inserted here -->
        </div>
    </div>
</div>

<script>
// ===== INITIALIZATION =====
$(document).ready(function() {
    initializeTheme();
    
    <?php if (isset($_SESSION['user_id'])): ?>
    // User is logged in - load data and show home
    loadFromDatabase();
    showScreen('home');
    <?php else: ?>
    // User not logged in - show login
    showScreen('login');
    <?php endif; ?>
});

// ===== RENDER FUNCTIONS =====
function renderMovies() {
    let html = '';
    if (allMovies.length === 0) {
        html = '<div class="no-results">No movies available</div>';
    } else {
        // Show only first 4 movies on home screen
        const homeMovies = allMovies.slice(0, 4);
        
        homeMovies.forEach(m => {
            // Properly escape all string values for JavaScript
            const title = (m.title || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const poster = (m.poster || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const description = (m.description || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, ' ');
            const genre = (m.genre || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const duration = (m.duration || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const rating = (m.rating || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const cast = (m.cast || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const release_date = (m.release_date || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const price = m.price || 0;
            const id = m.id || 0;
            
            html += `
                <div class="movie-card" onclick="showMovieDetails(
                    '${title}',
                    '${poster}',
                    '${description}',
                    '${genre}',
                    '${duration}',
                    ${price},
                    ${id},
                    '${rating}',
                    '${cast}',
                    '${release_date}'
                ); return false;">
                    <img src="${m.poster || 'https://via.placeholder.com/300x450?text=' + encodeURIComponent(m.title || 'Movie')}" alt="${m.title || 'Movie'}" style="width:100%; height:200px; object-fit:cover;" 
                         onerror="this.src='https://via.placeholder.com/300x450?text=${encodeURIComponent(m.title || 'Movie')}';">
                    <div class="rating-badge ${(m.rating || 'pg').toLowerCase().replace('-', '')}">${m.rating || 'PG'}</div>
                    <h3>${m.title || 'Untitled'}</h3>
                    <p>${m.genre || 'General'}</p>
                    <p class="price">₱${m.price || 0}</p>
                </div>
            `;
        });
    }
    $('#homeMoviesGrid').html(html);
}

function renderAllMovies() {
    let html = '';
    
    // FIX: Use only allMovies (now showing), NOT allComingSoonMovies
    if (allMovies.length === 0) {
        html = '<div class="no-results">No movies available</div>';
    } else {
        allMovies.forEach(m => {
            // Check if it's coming soon - but this will always be false for allMovies array
            const isComingSoon = false; // Force to false since allMovies only contains now showing
            
            // Escape all string values
            const title = (m.title || '').replace(/'/g, "\\'");
            const poster = (m.poster || '').replace(/'/g, "\\'");
            const description = (m.description || '').replace(/'/g, "\\'").replace(/\n/g, ' ');
            const genre = (m.genre || '').replace(/'/g, "\\'");
            const duration = (m.duration || '').replace(/'/g, "\\'");
            const rating = (m.rating || '').replace(/'/g, "\\'");
            const cast = (m.cast || '').replace(/'/g, "\\'");
            const release_date = (m.release_date || '').replace(/'/g, "\\'");
            const price = m.price || 0;
            const id = m.id || 0;
            
            html += `
                <div class="movie-card" onclick="showMovieDetails(
                    '${title}',
                    '${poster}',
                    '${description}',
                    '${genre}',
                    '${duration}',
                    ${price},
                    ${id},
                    '${rating}',
                    '${cast}',
                    '${release_date}'
                ); return false;">
                    <div class="rating-badge ${(rating || 'pg').toLowerCase().replace('-', '')}">${rating || 'PG'}</div>
                    <img src="${poster || 'https://via.placeholder.com/300x450?text=' + encodeURIComponent(title || 'Movie')}" alt="${title || 'Movie'}" style="width:100%; height:200px; object-fit:cover;" 
                         onerror="this.src='https://via.placeholder.com/300x450?text=${encodeURIComponent(title || 'Movie')}';">
                    <h3>${title || 'Untitled'}</h3>
                    <p>${genre || 'General'}</p>
                    <p class="price">₱${price || 0}</p>
                </div>
            `;
        });
    }
    $('#allMoviesGrid').html(html);
}

function renderComingSoon() {
    let html = '';
    if (allComingSoonMovies.length === 0) {
        html = '<div class="no-results">No coming soon movies</div>';
    } else {
        allComingSoonMovies.forEach(m => {
            // Double-check that it's actually a coming soon movie
            if (m.is_coming_soon != 1) return;
            
            const releaseDate = m.release_date ? new Date(m.release_date + 'T00:00:00') : new Date();
            const formattedDate = releaseDate.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
            
            // Properly escape all string values for JavaScript
            const title = (m.title || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const poster = (m.poster || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const description = (m.description || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;').replace(/\n/g, ' ');
            const genre = (m.genre || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const duration = (m.duration || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const rating = (m.rating || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const cast = (m.cast || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            const release_date = (m.release_date || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
            
            html += `
                <div class="movie-card">
                    <div class="coming-soon-badge">Coming Soon</div>
                    <div class="rating-badge ${(m.rating || 'pg').toLowerCase().replace('-', '')}">${m.rating || 'PG'}</div>
                    <img src="${m.poster || 'https://via.placeholder.com/300x450?text=' + encodeURIComponent(m.title || 'Movie')}" alt="${m.title || 'Movie'}" style="width:100%; height:200px; object-fit:cover;" 
                         onerror="this.src='https://via.placeholder.com/300x450?text=${encodeURIComponent(m.title || 'Movie')}';">
                    <h3>${m.title || 'Untitled'}</h3>
                    <p>${m.genre || 'General'}</p>
                    <p style="color:var(--accent-yellow); font-size:0.8rem; margin:5px 12px;">${formattedDate}</p>
                    <button onclick="viewComingSoonDetails(
                        '${title}',
                        '${poster}',
                        '${description}',
                        '${genre}',
                        '${duration}',
                        '${release_date}',
                        '${rating}'
                    ); return false;" style="margin:5px 12px 12px 12px; width:calc(100% - 24px); padding:8px; background:rgba(76,201,240,0.2); color:#4cc9f0; border:1px solid #4cc9f0; border-radius:30px; font-size:0.8rem; cursor:pointer;">Watch Trailer</button>
                </div>
            `;
        });
    }
    $('#comingSoonGrid').html(html);
}

function renderPromos() {
    let promosHtml = '';
    if (promos.length === 0) {
        promosHtml = '<div class="no-results">No promos available</div>';
    } else {
        promos.forEach(p => {
            const isRedeemed = userRedeemedPromos[p.id] ? true : false;
            // Use points_required from database, fallback to points or 0
            const pointsRequired = p.points_required || p.points || 0;
            
            // Debug log to check values
            console.log('Promo:', p.title, 'Points Required:', pointsRequired, 'User Points:', userPoints, 'Is Redeemed:', isRedeemed);
            
            promosHtml += `
                <div class="promo-card ${isRedeemed ? 'redeemed' : ''}">
                    ${!isRedeemed ? '<div class="promo-badge">HOT</div>' : ''}
                    <div class="promo-icon"><i class="fas ${p.icon || 'fa-gift'}"></i></div>
                    <div class="promo-title">${p.title || 'Promo'}</div>
                    <div class="promo-description">${p.description || ''}</div>
                    <div class="promo-cost">
                        <div class="points-required"><i class="fas fa-coins"></i> ${pointsRequired}</div>
                        ${isRedeemed ? 
                            '<div class="redeemed-badge">Redeemed</div>' : 
                            `<button class="redeem-btn" onclick="claimPromo(${p.id}, ${pointsRequired}); return false;" ${userPoints < pointsRequired ? 'disabled' : ''}>Redeem</button>`
                        }
                    </div>
                </div>
            `;
        });
    }
    $('#promosGrid').html(promosHtml);
    
    let rewardsHtml = '';
    let hasRewards = false;
    let totalPointsSpent = 0;
    let totalPointsEarned = 0;
    
    // Calculate total points earned from bookings
    userBookings.forEach(booking => {
        totalPointsEarned += parseInt(booking.points_earned || 0);
    });
    
    for (let id in userRedeemedPromos) {
        if (userRedeemedPromos.hasOwnProperty(id)) {
            hasRewards = true;
            const promo = promos.find(p => p.id == id);
            if (!promo) continue;
            
            const pointsSpent = parseInt(userRedeemedPromos[id].points_spent || 0);
            totalPointsSpent += pointsSpent;
            
            const date = new Date(userRedeemedPromos[id].redeemed_at);
            const formattedDate = date.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
            
            rewardsHtml += `
                <div class="reward-item">
                    <div class="reward-info">
                        <div class="reward-title">${promo.title || 'Reward'}</div>
                        <div class="reward-date">${formattedDate}</div>
                    </div>
                    <div class="reward-cost"><i class="fas fa-coins"></i> -${pointsSpent}</div>
                    <div class="reward-actions">
                        <button class="save-qr-btn" onclick="showRewardTicket(${id}); return false;"><i class="fas fa-ticket-alt"></i> Ticket</button>
                    </div>
                </div>
            `;
        }
    }
    
    if (!hasRewards) {
        rewardsHtml = `
            <div class="no-rewards">
                <i class="fas fa-gift"></i>
                <h3>No Rewards Yet</h3>
                <p>Redeem rewards with your points!</p>
            </div>
        `;
    } else {
        // Add a summary of total points earned and spent
        rewardsHtml += `
            <div style="margin-top: 15px; padding: 15px; background: rgba(255, 140, 66, 0.1); border-radius: 20px; border: 1px solid rgba(255, 140, 66, 0.3);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                    <span style="color: var(--text-secondary);">Total Points Earned from Bookings:</span>
                    <span style="font-weight: 700; color: var(--accent-green);">+${totalPointsEarned}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="color: var(--text-secondary);">Total Points Spent on Rewards:</span>
                    <span style="font-weight: 700; color: var(--accent-primary);">-${totalPointsSpent}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(255, 140, 66, 0.3);">
                    <span style="color: var(--text-primary); font-weight: 600;">Current Balance:</span>
                    <span style="font-weight: 800; color: var(--accent-yellow);">${userPoints} points</span>
                </div>
            </div>
        `;
    }
    
    $('#rewardsList').html(rewardsHtml);
}

function renderBookings() {
    if (userBookings.length === 0) {
        $('#bookingsList').html(`
            <div class="no-bookings">
                <i class="fas fa-ticket-alt"></i>
                <h3>No Bookings Yet</h3>
                <p>Book your first movie now!</p>
                <button class="action-btn btn-primary" onclick="showScreen('home'); return false;">Browse Movies</button>
            </div>
        `);
        return;
    }
    
    // Calculate total points earned from all bookings
    let totalPointsEarned = 0;
    
    let html = '';
    userBookings.forEach(b => {
        const pointsEarned = parseInt(b.points_earned || 0);
        totalPointsEarned += pointsEarned;
        
        const showDate = b.show_date ? new Date(b.show_date + 'T00:00:00') : new Date();
        const formattedShowDate = showDate.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric' });
        const bookingDate = b.booking_time ? new Date(b.booking_time) : new Date();
        const formattedBookingDate = bookingDate.toLocaleDateString('en-US', { month:'short', day:'numeric', year:'numeric', hour:'2-digit', minute:'2-digit' });
        
        html += `
            <div class="booking-card">
                <div class="booking-header">
                    <div class="booking-movie">${b.movie || 'Movie'}</div>
                    <div class="booking-date">${formattedBookingDate}</div>
                </div>
                <div class="booking-details">
                    <div class="booking-detail">
                        <span class="detail-label">Show</span>
                        <span class="detail-value">${formattedShowDate} ${b.show_time || ''}</span>
                    </div>
                    <div class="booking-detail">
                        <span class="detail-label">Seats</span>
                        <span class="detail-value">${b.seats || ''}</span>
                    </div>
                    <div class="booking-detail">
                        <span class="detail-label">Total</span>
                        <span class="detail-value">₱${b.total || 0}</span>
                    </div>
                    <div class="booking-detail">
                        <span class="detail-label">Points Earned</span>
                        <span class="detail-value" style="color: var(--accent-green); font-weight: 700;">+${pointsEarned}</span>
                    </div>
                    <div class="booking-detail" style="grid-column: span 2;">
                        <span class="detail-label">Ref #</span>
                        <span class="detail-value" style="font-family:'Space Grotesk', monospace;">${b.booking_reference || ''}</span>
                    </div>
                </div>
                <div class="booking-actions">
                    <button class="action-btn-small btn-primary" onclick="viewBookingTicket(
                        ${b.booking_id || 0},
                        '${(b.movie || '').replace(/'/g, "\\'")}',
                        '${b.show_date || ''}',
                        '${b.show_time || ''}',
                        '${b.seats || ''}',
                        ${b.total || 0},
                        ${pointsEarned},
                        '${b.booking_time || ''}',
                        '${b.booking_reference || ''}'
                    ); return false;">
                        <i class="fas fa-ticket-alt"></i> View
                    </button>
                    <button class="action-btn-small btn-secondary" onclick="printBookingTicket(
                        ${b.booking_id || 0},
                        '${(b.movie || '').replace(/'/g, "\\'")}',
                        '${b.show_date || ''}',
                        '${b.show_time || ''}',
                        '${b.seats || ''}',
                        ${b.total || 0},
                        ${pointsEarned},
                        '${b.booking_time || ''}',
                        '${b.booking_reference || ''}'
                    ); return false;">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        `;
    });
    
    // Add a summary of total points earned
    html += `
        <div style="margin-top: 20px; padding: 15px; background: var(--bg-card); border-radius: 30px; border: 1px solid var(--glass-border);">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 1rem; color: var(--text-secondary);">Total Points Earned from Bookings:</span>
                <span style="font-size: 1.3rem; font-weight: 700; color: var(--accent-green);">+${totalPointsEarned}</span>
            </div>
        </div>
    `;
    
    $('#bookingsList').html(html);
}

// ===== AUTH FUNCTIONS =====
function handleSignup(e) {
    e.preventDefault();
    const username = $('#signupUsername').val();
    const password = $('#signupPassword').val();
    const signupError = $('#signupError');
    
    const reqs = {
        length: password.length >= 12,
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*()\-_=+{};:,<.>]/.test(password),
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password)
    };
    
    if (Object.values(reqs).every(v => v === true)) {
        $.ajax({
            url: 'api.php?action=signup',
            type: 'POST',
            data: JSON.stringify({
                username: username,
                password: password
            }),
            contentType: 'application/json',
            success: function(response) {
                if (response.success) {
                    showToast('Account created successfully! Please login.', 'success');
                    showScreen('login');
                    $('#signupUsername').val('');
                    $('#signupPassword').val('');
                } else {
                    signupError.text(response.message || 'Username already exists').show();
                }
            }
        });
    } else {
        signupError.text('Please meet all password requirements').show();
    }
}

function handleLogin(e) {
    e.preventDefault();
    e.stopPropagation();
    
    const username = $('#loginUsername').val();
    const password = $('#loginPassword').val();
    const loginError = $('#loginError');
    
    loginError.hide().text('');
    
    $.ajax({
        url: 'api.php?action=login',
        type: 'POST',
        data: JSON.stringify({
            username: username,
            password: password
        }),
        contentType: 'application/json',
        success: function(response) {
            if (response.success) {
                isLoggedIn = true;
                userPoints = response.points;
                $('#userPoints').text(response.points);
                showToast('Login successful!', 'success');
                setTimeout(function() {
                    location.reload(); // Reload to get session data
                }, 500);
            } else {
                loginError.text(response.message || 'Invalid username or password').show();
            }
        },
        error: function() {
            loginError.text('Login failed. Please try again.').show();
        }
    });
    
    return false;
}

function logout() {
    $.ajax({
        url: 'api.php?action=logout',
        type: 'POST',
        success: function() {
            isLoggedIn = false;
            showToast('Logged out successfully', 'success');
            setTimeout(function() {
                location.reload();
            }, 500);
        }
    });
}

// ===== SCREEN NAVIGATION =====
function showScreen(screenId) {
    // Remove all screen-specific classes first
    $('body').removeClass('movie-details-active seats-active payment-active ticket-active auth-screen');
    
    // Hide all containers
    $('.home-container, .seats-container, .promos-container, .ticket-container, .bookings-container, .payment-container').hide();
    
    // Show the requested screen
    $('#' + screenId).show();
    
    // Add appropriate class based on screen
    if (screenId === 'movieDetails') {
        $('body').addClass('movie-details-active');
    } else if (screenId === 'booking') {
        $('body').addClass('seats-active');
    } else if (screenId === 'payment') {
        $('body').addClass('payment-active');
    } else if (screenId === 'ticket') {
        $('body').addClass('ticket-active');
    } else if (screenId === 'login' || screenId === 'signup') {
        $('body').addClass('auth-screen');
    }
    
    // Hide modals
    if (screenId !== 'comingSoon' && screenId !== 'allMovies') $('#movieDetailsModal').hide();
    
    // Handle tab buttons visibility
    if (screenId === 'home' || screenId === 'comingSoon' || screenId === 'allMovies') {
        $('.tab-buttons').show();
    } else {
        $('.tab-buttons').hide();
    }
    
    // Update bottom nav active state
    $('.bottom-nav-item').removeClass('active');
    if (screenId === 'home') {
        $('#bottomHome').addClass('active');
        renderMovies();
    } else if (screenId === 'allMovies') {
        $('#bottomBrowse').addClass('active');
        renderAllMovies();
    } else if (screenId === 'comingSoon') {
        $('#bottomBrowse').addClass('active');
        renderComingSoon();
    } else if (screenId === 'movieDetails') {
        $('#bottomBrowse').addClass('active');
    } else if (screenId === 'bookings') {
        $('#bottomBookings').addClass('active');
        renderBookings();
    } else if (screenId === 'promos') {
        $('#bottomPromos').addClass('active');
        renderPromos();
    }
    
    closeMobileMenu();
    if (searchOverlayOpen) toggleSearchOverlay();
}

function setActiveNav(element) {
    $('#mobileMenu li a').removeClass('active');
    $(element).addClass('active');
}

// ===== TOAST NOTIFICATION =====
function showToast(message, type = 'info') {
    const toast = $('#toast');
    toast.removeClass('success error').addClass(type);
    toast.html(`<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> ${message}`);
    toast.addClass('show');
    setTimeout(() => toast.removeClass('show'), 3000);
}

// ===== PASSWORD VALIDATION =====
$(document).on('input', '#signupPassword', function() {
    const pwd = $(this).val();
    const reqs = {
        length: pwd.length >= 12,
        number: /[0-9]/.test(pwd),
        special: /[!@#$%^&*()\-_=+{};:,<.>]/.test(pwd),
        uppercase: /[A-Z]/.test(pwd),
        lowercase: /[a-z]/.test(pwd)
    };
    
    $('#req-length').html((reqs.length ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>') + ' 12+ characters');
    $('#req-number').html((reqs.number ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>') + ' One number');
    $('#req-special').html((reqs.special ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>') + ' One special');
    $('#req-uppercase').html((reqs.uppercase ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>') + ' One uppercase');
    $('#req-lowercase').html((reqs.lowercase ? '<i class="fas fa-check-circle"></i>' : '<i class="fas fa-times-circle"></i>') + ' One lowercase');
    
    $('#req-length, #req-number, #req-special, #req-uppercase, #req-lowercase').css('color', function() {
        return $(this).find('i').hasClass('fa-check-circle') ? 'var(--accent-green)' : 'var(--text-secondary)';
    });
});

// ===== TAB BUTTONS =====
$(document).on('click', '#nowShowingBtn, #nowShowingBtn2', function() {
    $('.tab-buttons button').removeClass('active');
    $(this).addClass('active');
    showScreen('home');
});

$(document).on('click', '#allMoviesBtn, #allMoviesBtn2', function() {
    showScreen('allMovies');
    renderAllMovies();
    return false;
});

$(document).on('click', '#comingSoonBtn, #comingSoonBtn2', function() {
    $('.tab-buttons button').removeClass('active');
    $(this).addClass('active');
    showScreen('comingSoon');
});

// ===== BOTTOM NAVIGATION =====
$('#bottomHome').on('click', function() {
    $('.bottom-nav-item').removeClass('active');
    $(this).addClass('active');
    showScreen('home');
});

$('#bottomBrowse').on('click', function() {
    $('.bottom-nav-item').removeClass('active');
    $(this).addClass('active');
    showScreen('allMovies');
    renderAllMovies();
});

$('#bottomBookings').on('click', function() {
    $('.bottom-nav-item').removeClass('active');
    $(this).addClass('active');
    showScreen('bookings');
});

$('#bottomPromos').on('click', function() {
    $('.bottom-nav-item').removeClass('active');
    $(this).addClass('active');
    showScreen('promos');
});

// ===== QR CODE =====
function generateQRCode(text, containerId) {
    const container = document.getElementById(containerId);
    if (!container) return;
    
    container.innerHTML = '';
    
    if (typeof QRCode === 'undefined') {
        container.innerHTML = '<div style="padding:10px; text-align:center;">QR unavailable</div>';
        return;
    }
    
    try {
        QRCode.toCanvas(container, text, { width: 140, margin: 1 }, function(error) {
            if (error) {
                container.innerHTML = '<div style="padding:10px;">' + text.substring(0,15) + '...</div>';
            }
        });
    } catch(e) {
        container.innerHTML = '<div style="padding:10px;">' + text.substring(0,15) + '...</div>';
    }
}

// ===== MOVIE DETAILS =====
function showMovieDetails(title, poster, description, genre, duration, price, movieId, rating, cast, release_date) {
    console.log('showMovieDetails called with:', {title, poster, description, genre, duration, price, movieId, rating, cast, release_date}); // Debug log
    
    // Set default values if parameters are undefined
    currentMovie = title || 'Movie';
    currentPrice = price || 0;
    currentMovieId = movieId || 0;
    
    console.log('Set currentMovieId to:', currentMovieId);
    
    $('#detailsTitle').text(title || 'Movie');
    
    // Handle poster - check if it's a valid string
    if (poster && poster !== 'undefined' && poster !== 'null' && poster !== '') {
        $('#detailsPoster').attr('src', poster);
    } else {
        $('#detailsPoster').attr('src', 'https://via.placeholder.com/300x450?text=' + encodeURIComponent(title || 'Movie'));
    }
    
    $('#detailsDescription').text(description || 'No description available');
    $('#detailsGenre').text(genre || 'General');
    $('#detailsDuration').text(duration || 'N/A');
    $('#detailsPrice').text(price || 0);
    
    // Set release date - handle undefined or null
    if (release_date && release_date !== 'undefined' && release_date !== 'null' && release_date !== '') {
        try {
            const releaseDate = new Date(release_date + 'T00:00:00');
            // Check if date is valid
            if (!isNaN(releaseDate.getTime())) {
                const formattedDate = releaseDate.toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
                $('#detailsReleaseDate').text(formattedDate);
            } else {
                $('#detailsReleaseDate').text('Coming Soon');
            }
        } catch(e) {
            console.log('Error parsing release date:', e);
            $('#detailsReleaseDate').text('Coming Soon');
        }
    } else {
        $('#detailsReleaseDate').text('Coming Soon');
    }
    
    // Set rating display
    const ratingColors = {
        'G': '#06d6a0',
        'PG': '#4cc9f0',
        'PG-13': '#ffd166',
        'R': '#ff8c42',
        'NC-17': '#1e3a8a'
    };
    
    const displayRating = rating || 'PG';
    $('#detailsRatingDisplay').text(displayRating).css('background', ratingColors[displayRating] || '#ff8c42');
    $('#detailsSubtitle').text('Now showing in theaters');
    
    // Create movie object for trailer lookup
    const movie = {
        title: title,
        trailer_url: ''
    };
    
    // Find trailer from allMovies or allComingSoonMovies
    const foundMovie = allMovies.find(m => m.id == movieId) || allComingSoonMovies.find(m => m.id == movieId);
    if (foundMovie && foundMovie.trailer_url) {
        movie.trailer_url = foundMovie.trailer_url;
    }
    
    // Find trailer using database URL first, then fallback to hardcoded
    const trailer = getTrailerFromDatabase(movie);
    
    if (trailer) {
        currentMovieTrailer = trailer;
        // If we have a trailer from database, use the poster as thumbnail
        // or try to get YouTube thumbnail if it's a YouTube ID
        if (movie.trailer_url && movie.trailer_url.trim() !== '') {
            // Check if it's a full URL or just an ID
            if (movie.trailer_url.includes('youtube.com') || movie.trailer_url.includes('youtu.be')) {
                // Extract video ID from URL
                let videoId = '';
                if (movie.trailer_url.includes('youtube.com/watch?v=')) {
                    videoId = movie.trailer_url.split('v=')[1]?.split('&')[0];
                } else if (movie.trailer_url.includes('youtu.be/')) {
                    videoId = movie.trailer_url.split('youtu.be/')[1]?.split('?')[0];
                }
                if (videoId) {
                    $('#detailsPoster').attr('src', `https://img.youtube.com/vi/${videoId}/maxresdefault.jpg`);
                }
            } else {
                // Assume it's just a video ID
                $('#detailsPoster').attr('src', `https://img.youtube.com/vi/${movie.trailer_url}/maxresdefault.jpg`);
            }
        } else {
            $('#detailsPoster').attr('src', trailer.thumbnail);
        }
        console.log('Trailer found for:', title, trailer);
    } else {
        currentMovieTrailer = null;
        if (poster && poster !== 'undefined' && poster !== '') {
            $('#detailsPoster').attr('src', poster);
        }
        console.log('No trailer found for:', title);
    }
    
// Render cast
const castList = $('#castMiniList');
castList.empty();

if (cast && cast.trim() !== '' && cast !== 'undefined' && cast !== 'null') {
    // Split by comma and clean up
    const castArray = cast.split(',').map(c => c.trim()).filter(c => c !== '');
    
    // Use a Set to remove duplicates
    const uniqueCast = [...new Set(castArray)];
    
    // Limit to maximum 5 cast members to prevent overflow
    const limitedCast = uniqueCast.slice(0, 5);
    
    limitedCast.forEach(actor => {
        if (actor) {
            // Get initials from the full name
            const nameParts = actor.split(' ');
            const initials = nameParts.length > 1 
                ? (nameParts[0][0] + nameParts[nameParts.length-1][0]).toUpperCase()
                : actor.substring(0, 2).toUpperCase();
            
            castList.append(`
                <div class="cast-mini-item">
                    <div class="cast-mini-avatar">${initials}</div>
                    <div class="cast-mini-name">${actor}</div>
                    <div class="cast-mini-character">Actor</div>
                </div>
            `);
        }
    });
    
    // If there are more cast members than shown, add a "+X more" indicator
    if (uniqueCast.length > 5) {
        castList.append(`
            <div class="cast-mini-item" style="justify-content: center;">
                <div class="cast-mini-avatar" style="background: var(--accent-gradient);">+${uniqueCast.length - 5}</div>
                <div class="cast-mini-name">More</div>
                <div class="cast-mini-character">Cast</div>
            </div>
        `);
    }
} else {
    // Default cast if none provided
    castList.append(`
        <div class="cast-mini-item">
            <div class="cast-mini-avatar">CS</div>
            <div class="cast-mini-name">Cast information</div>
            <div class="cast-mini-character">coming soon</div>
        </div>
    `);
}

// Pricing tag
const pricingTag = $('<div class="pricing-tag">...</div>');
    
    // Generate dates
    const datesContainer = $('#datesScroll');
    datesContainer.empty();
    const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    for (let i = 0; i < 7; i++) {
        const date = new Date();
        date.setDate(date.getDate() + i);
        
        const dayName = weekdays[date.getDay()];
        const dayNum = date.getDate();
        const month = months[date.getMonth()];
        const dateVal = date.toISOString().split('T')[0];
        const isActive = i === 0 ? 'active' : '';
        
        const dateCard = $(`
            <div class="date-card ${isActive}" data-date="${dateVal}">
                <span class="day">${dayName}</span>
                <span class="date-num">${dayNum}</span>
                <span class="month">${month}</span>
            </div>
        `);
        
        dateCard.on('click', function() {
            $('.date-card').removeClass('active');
            $(this).addClass('active');
            selectedDate = $(this).data('date');
        });
        
        datesContainer.append(dateCard);
    }

    selectedDate = new Date().toISOString().split('T')[0];
    
    // Generate times
    const timeContainer = $('#timeGrid');
    timeContainer.empty();
    const times = ['10:00', '13:00', '16:00', '19:00', '22:00'];
    const timeLabels = ['10:00 AM', '1:00 PM', '4:00 PM', '7:00 PM', '10:00 PM'];

    times.forEach((time, index) => {
        const timeDisplay = time;
        const period = parseInt(time.split(':')[0]) >= 12 ? 'PM' : 'AM';
        
        const timeCard = $(`
            <div class="time-card ${index === 0 ? 'active' : ''}" data-time="${timeLabels[index]}">
                <span class="time-value">${timeDisplay}</span>
                <span class="time-period">${period}</span>
            </div>
        `);
        
        timeCard.on('click', function() {
            $('.time-card').removeClass('active');
            $(this).addClass('active');
            selectedTime = $(this).data('time');
        });
        
        timeContainer.append(timeCard);
    });

    selectedTime = timeLabels[0];
    
    // Show the movie details screen
    $('.home-container, .seats-container, .promos-container, .ticket-container, .bookings-container, .payment-container').hide();
    $('#movieDetails').show();
    $('body').removeClass('auth-screen').addClass('movie-details-active');
    $('.tab-buttons').hide();
    $('.bottom-nav-item').removeClass('active');
    $('#bottomBrowse').addClass('active');
    
    console.log('Movie details loaded successfully:', title);
}

function goBackFromMovieDetails() {
    $('body').removeClass('movie-details-active');
    showScreen('home');
}

function goBackFromBooking() {
    $('body').removeClass('seats-active');
    $('body').addClass('movie-details-active');
    showScreen('movieDetails');
}

function goBackFromPayment() {
    $('body').removeClass('payment-active');
    showScreen('booking');
}

function goBackFromAllMovies() {
    showScreen('home');
    $('#nowShowingBtn').addClass('active');
    $('#comingSoonBtn').removeClass('active');
}

function viewComingSoonDetails(title, poster, description, genre, duration, releaseDate, rating) {
    currentMovie = title;
    currentMovieId = null;
    
    // Find the movie in allComingSoonMovies to get the trailer_url
    const movie = allComingSoonMovies.find(m => m.title === title);
    const trailer = getTrailerFromDatabase(movie || { title: title });
    
    const d = releaseDate ? new Date(releaseDate + 'T00:00:00') : new Date();
    const formatted = d.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    
    const modalContent = $('.modal-content');
    modalContent.empty();
    
    let modalHTML = `
        <span id="closeModal">&times;</span>
    `;
    
    if (trailer && trailer.videoUrl) {
        modalHTML += `
            <div class="trailer-thumbnail-container" style="position: relative; width: 100%; margin-top: 15px; margin-bottom: 10px; cursor: pointer;" onclick="playTrailer('${trailer.videoUrl}', '${trailer.title}'); return false;">
                <img src="${trailer.thumbnail || poster || 'https://via.placeholder.com/300x450'}" alt="${title} trailer" style="width:100%; border-radius:20px; margin-bottom:10px;">
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); width: 48px; height: 48px; background: rgba(255, 255, 255, 0.25); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.3rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); border: 1.5px solid rgba(255, 255, 255, 0.5); backdrop-filter: blur(4px);">
                    <i class="fas fa-play" style="margin-left: 3px;"></i>
                </div>
            </div>
        `;
    } else {
        modalHTML += `
            <img src="${poster || 'https://via.placeholder.com/300x450'}" alt="${title}" style="width:100%; border-radius:20px; margin-bottom:10px;">
        `;
    }
    
    modalHTML += `
        <h2 style="color:var(--accent-primary); font-size:1.4rem; margin-bottom: 10px;">${title || 'Movie'}</h2>
        <p style="color:var(--text-secondary); font-size:0.95rem; line-height:1.5; margin-bottom: 10px;">${description || 'No description available'}</p>
        <p style="font-size:0.9rem; margin-bottom: 5px;"><strong>Genre:</strong> ${genre || 'General'}</p>
        <p style="font-size:0.9rem; margin-bottom: 5px;"><strong>Duration:</strong> ${duration || 'N/A'}</p>
        <p style="color:var(--accent-yellow); font-size:0.9rem; margin-bottom: 10px;"><strong>Release Date:</strong> ${formatted}</p>
        <div class="movie-rating-badge ${(rating || 'pg').toLowerCase().replace('-', '')}" style="margin:10px auto; width:fit-content;">${rating || 'PG'}</div>
    `;
    
    modalContent.html(modalHTML);
    $('#movieDetailsModal').css('display','flex').fadeIn();
}

// ===== BOOKING =====
function proceedToBooking() {
    console.log('proceedToBooking called with:', {currentMovie, currentPrice, selectedDate, selectedTime, currentMovieId});
    
    if (!currentMovie) {
        showToast('Movie information missing', 'error');
        return;
    }
    
    if (!selectedDate) {
        showToast('Please select a date', 'error');
        return;
    }
    
    if (!selectedTime) {
        showToast('Please select a time', 'error');
        return;
    }
    
    if (!currentMovieId) {
        console.warn('currentMovieId is not set, but continuing with movie title only');
        // This is not fatal - we can still book with just the title
    }
    
    showMovieBooking(currentMovie, currentPrice, selectedDate, selectedTime);
}

function showMovieBooking(title, price, showDate, showTime) {
    $('#movieInput').val(title);
    $('#showDateInput').remove();
    $('#showTimeInput').remove();
    $('<input>').attr({type:'hidden',id:'showDateInput',name:'show_date',value:showDate}).appendTo('#bookingForm');
    $('<input>').attr({type:'hidden',id:'showTimeInput',name:'show_time',value:showTime}).appendTo('#bookingForm');
    
    const seatGrid = $('#seatGrid');
    seatGrid.empty();
    const selectedSeats = [];
    
    const showInfo = $(`<div class="show-info" style="background:var(--bg-card); padding:20px; border-radius:30px; margin-bottom:20px; border:1px solid var(--glass-border);"><h3 style="font-size:1.3rem; margin-bottom:5px;">${title}</h3><p style="color:var(--text-secondary);">${formatDate(showDate)} at ${showTime}</p></div>`);
    $('#showInfoContainer').empty().append(showInfo);
    
    const key = title + '|' + showDate + '|' + showTime;
    const occupied = occupiedSeatsData[key] || [];
    
    generateSeatLayout(seatGrid, selectedSeats, occupied, price);
}

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
                    seat.addClass('occupied');
                } else {
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
                    seat.addClass('occupied');
                } else {
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 17; num <= 21; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('occupied');
                } else {
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
                    seat.addClass('occupied');
                } else {
                    seat.addClass('available'); // Add this line to ensure it's styled as available
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 10; num <= 20; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('occupied');
                } else {
                    seat.click(createSeatHandler(seat, selectedSeats, price));
                }
                rowDiv.append(seat);
            }
            
            rowDiv.append('<div style="width:8px"></div>');
            
            for (let num = 21; num <= 29; num++) {
                const seatId = row + num;
                const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
                if (occupied.includes(seatId)) {
                    seat.addClass('occupied');
                } else {
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
                seat.addClass('occupied');
            } else {
                seat.click(createSeatHandler(seat, selectedSeats, price));
            }
            rowDiv.append(seat);
        }
        
        rowDiv.append('<div style="width:10px"></div>');
        
        for (let num = 14; num <= 26; num++) {
            const seatId = row + num;
            const seat = $('<div class="seat"></div>').text(num).attr('data-seat', seatId);
            if (occupied.includes(seatId)) {
                seat.addClass('occupied');
            } else {
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

    showScreen('booking');
}

function createSeatHandler(seat, selectedSeats, price) {
    return function() {
        console.log('Seat clicked:', $(this).attr('data-seat')); // Debug log
        
        if ($(this).hasClass('occupied')) {
            console.log('Seat is occupied, cannot select');
            return;
        }
        
        $(this).toggleClass('selected');
        const s = $(this).attr('data-seat');
        if ($(this).hasClass('selected')) {
            selectedSeats.push(s);
            console.log('Selected seats:', selectedSeats);
        } else {
            const index = selectedSeats.indexOf(s);
            if (index > -1) selectedSeats.splice(index, 1);
            console.log('Unselected seat, remaining:', selectedSeats);
        }
        
        const total = selectedSeats.length * price;
        const points = selectedSeats.length * 10;
        
        $('#totalPrice').val(total);
        $('#pointsInput').val(points);
        $('#seatsInput').val(selectedSeats.join(','));
        $('#selectedSeatsCount').text(selectedSeats.length);
        $('#displayTotal').text('₱' + total);
        $('#displayPoints').text(points);
        
        console.log('Updated total:', total, 'points:', points);
    };
}

function formatDate(dateStr) {
    return new Date(dateStr + 'T00:00:00').toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
}

// ===== PAYMENT =====
function selectPaymentMethod(method) {
    $('.payment-method').removeClass('selected');
    if (method === 'gcash') {
        $('.payment-method:contains("GCash")').addClass('selected');
        $('.gcash-fields').show();
        $('.paymaya-fields').hide();
    } else {
        $('.payment-method:contains("PayMaya")').addClass('selected');
        $('.gcash-fields').hide();
        $('.paymaya-fields').show();
    }
    selectedPaymentMethod = method;
    $('#paymentForm').show();
    $('#paymentMethodTitle').text(method === 'gcash' ? 'GCash Payment' : 'PayMaya Payment');
    
    updatePaymentSummary();
}

function updatePaymentSummary() {
    $('#paymentMovie').text($('#movieInput').val());
    $('#paymentSeats').text($('#seatsInput').val());
    $('#paymentTotal').text('₱' + $('#totalPrice').val());
}

function proceedToPayment() {
    if (!$('#seatsInput').val()) { 
        showToast('Select seats', 'error'); 
        return; 
    }
    showScreen('payment');
    updatePaymentSummary();
}

function processPayment() {
    if (!selectedPaymentMethod) { 
        showToast('Select payment method', 'error'); 
        return; 
    }
    
    let valid = false;
    if (selectedPaymentMethod === 'gcash') {
        const num = $('#gcashNumber').val().replace(/\s/g,'');
        const name = $('#gcashName').val().trim();
        valid = num && name && num.length===11 && num.startsWith('09');
        if (!valid) showToast('Enter valid GCash details', 'error');
    } else {
        const num = $('#paymayaNumber').val().replace(/\s/g,'');
        const name = $('#paymayaName').val().trim();
        valid = num && name && num.length===11 && num.startsWith('09');
        if (!valid) showToast('Enter valid PayMaya details', 'error');
    }
    
    if (!valid) return;
    
    $('#paymentForm').hide();
    $('#paymentProcessing').show();
    
    setTimeout(() => {
        // Get the current user ID from PHP session
        const userId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>;
        
        if (!userId) {
            showToast('User not logged in', 'error');
            $('#paymentProcessing').hide();
            $('#paymentForm').show();
            return;
        }
        
        const movieTitle = $('#movieInput').val();
        const bookingReference = 'CB' + Date.now() + Math.floor(Math.random() * 1000);
        const totalPrice = parseFloat($('#totalPrice').val());
        const pointsEarned = parseInt($('#pointsInput').val());
        
        const newBooking = {
            movie_id: currentMovieId,
            movie_title: movieTitle,
            show_date: $('#showDateInput').val(),
            show_time: $('#showTimeInput').val(),
            seats: $('#seatsInput').val(),
            total_price: totalPrice,
            points_earned: pointsEarned,
            booking_time: new Date().toISOString().slice(0, 19).replace('T', ' '),
            booking_reference: bookingReference,
            user_id: userId
        };

        console.log('Saving booking with data:', newBooking); // Debug log
        
        // Add to local array for immediate display - using correct field names
        userBookings.unshift({
            booking_id: bookingReference,
            movie: movieTitle,
            show_date: newBooking.show_date,
            show_time: newBooking.show_time,
            seats: newBooking.seats,
            total: totalPrice, // For display
            points_earned: pointsEarned,
            booking_time: newBooking.booking_time,
            booking_reference: bookingReference
        });
        
        renderBookings();
        
        // Update user points
        userPoints += pointsEarned;
        updatePointsDisplay();
        $('#userPoints').text(userPoints);
        
        // Format date for display
        const d = new Date(newBooking.show_date + 'T00:00:00');
        const fd = d.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
        
        // Update ticket display
        $('#ticketMovie').text(movieTitle);
        $('#ticketDateTime').text(`${fd} at ${newBooking.show_time}`);
        $('#ticketSeats').text(newBooking.seats);
        $('#ticketTotal').text(totalPrice.toFixed(2));
        $('#ticketPoints').text(pointsEarned + ' points');
        $('#ticketTime').text(new Date(newBooking.booking_time).toLocaleString());
        $('#qrReference').text(bookingReference);
        
        generateQRCode(bookingReference, 'qrCodeContainer');
        
        $('#paymentProcessing').hide();
        showScreen('ticket');
        showToast('Booking confirmed!', 'success');
        
        // Save to database
        $.ajax({
            url: 'api.php?action=save_booking',
            type: 'POST',
            data: JSON.stringify(newBooking),
            contentType: 'application/json',
            success: function(response) {
                console.log('Booking saved to database:', response);
                if (response && !response.success) {
                    showToast('Booking saved locally but database had issue: ' + (response.message || 'Unknown error'), 'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error saving booking:', error);
                console.error('Response:', xhr.responseText);
                showToast('Booking saved locally but database sync failed', 'warning');
            }
        });
    }, 2000);
}

// Add this function to update points display with breakdown
function updatePointsDisplay() {
    // Calculate total points from bookings
    let totalEarned = 0;
    userBookings.forEach(booking => {
        totalEarned += parseInt(booking.points_earned || 0);
    });
    
    // Calculate total points spent on promos
    let totalSpent = 0;
    for (let id in userRedeemedPromos) {
        totalSpent += parseInt(userRedeemedPromos[id].points_spent || 0);
    }
    
    // Calculate expected balance (should match userPoints)
    let expectedBalance = totalEarned - totalSpent + 150; // Starting points
    
    console.log('Points breakdown:');
    console.log('  - Starting points: 150');
    console.log('  - Earned from bookings:', totalEarned);
    console.log('  - Spent on promos:', totalSpent);
    console.log('  - Current points:', userPoints);
    console.log('  - Expected balance:', expectedBalance);
    
    // Update the display
    $('#userPoints').text(userPoints);
    
    // If there's a discrepancy, log it
    if (userPoints !== expectedBalance && userPoints !== 150) {
        console.warn('Points discrepancy! User points:', userPoints, 'Expected:', expectedBalance);
    }
}

// ===== TICKET FUNCTIONS =====
function viewBookingTicket(id, movie, date, time, seats, total, points, bookingTime, ref) {
    const d = date ? new Date(date + 'T00:00:00') : new Date();
    const fd = d.toLocaleDateString('en-US', { weekday:'long', year:'numeric', month:'long', day:'numeric' });
    const bt = bookingTime ? new Date(bookingTime).toLocaleString() : new Date().toLocaleString();
    
    $('#ticketMovie').text(movie || 'Movie');
    $('#ticketDateTime').text(`${fd} at ${time || ''}`);
    $('#ticketSeats').text(seats || '');
    $('#ticketTotal').text(total || 0);
    $('#ticketPoints').text((points || 0) + ' points');
    $('#ticketTime').text(bt);
    $('#qrReference').text(ref || '');
    
    generateQRCode(ref || 'N/A', 'qrCodeContainer');
    showScreen('ticket');
}

function printTicket() { 
    window.print(); 
}

function downloadTicket() { 
    showToast('Use Print or Save as PDF', 'info'); 
}

function printBookingTicket(id, movie, date, time, seats, total, points, bookingTime, ref) {
    viewBookingTicket(id, movie, date, time, seats, total, points, bookingTime, ref);
    setTimeout(() => window.print(), 500);
}

// ===== PROMO FUNCTIONS =====
function claimPromo(id, points) {
    if (!confirm(`Redeem for ${points} points?`)) return;
    
    // Ensure points is a number
    points = parseInt(points);
    
    if (userPoints < points) {
        showToast('Not enough points', 'error');
        return;
    }
    
    // Find the promo
    let promo = promos.find(p => p.id == id);
    if (!promo) {
        showToast('Promo not found', 'error');
        return;
    }
    
    // Check if already redeemed
    if (userRedeemedPromos[id]) {
        showToast('You have already redeemed this promo', 'error');
        return;
    }
    
    // Deduct points
    userPoints -= points;
    $('#userPoints').text(userPoints);
    
    let ticketData = {
        reward_id: id,
        user_id: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>,
        title: promo.title || 'Reward',
        description: promo.description || '',
        points_spent: points,
        redeemed_at: new Date().toISOString(),
        reference: 'CB' + id + 'U' + Date.now()
    };
    
    // Add to local redeemed promos
    userRedeemedPromos[id] = {
        points_spent: points,
        redeemed_at: new Date().toISOString(),
        ticket_data: ticketData
    };
    
    // Update displays
    renderPromos();
    updatePointsDisplay();
    showToast('Reward redeemed successfully!', 'success');
    
    // Save to database
    $.ajax({
        url: 'api.php?action=redeem_promo',
        type: 'POST',
        data: JSON.stringify({
            user_id: <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>,
            promo_id: id,
            points_spent: points,
            ticket_data: ticketData
        }),
        contentType: 'application/json',
        success: function(response) {
            console.log('Promo redeemed in database:', response);
            if (response && response.success) {
                // Update user points from response if available
                if (response.new_points !== undefined) {
                    userPoints = parseInt(response.new_points);
                    $('#userPoints').text(userPoints);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error redeeming promo:', error);
            showToast('Promo saved locally but database sync failed', 'warning');
        }
    });
}

function showRewardTicket(id) {
    const data = userRedeemedPromos[id].ticket_data;
    const promo = promos.find(p => p.id == id);
    
    $('#rewardTicketTitle').text(promo ? promo.title : 'Reward');
    $('#ticketRewardName').text(promo ? promo.title : 'Reward');
    $('#ticketRewardDescription').text(promo ? promo.description : '');
    $('#ticketPointsSpent').text((data ? data.points_spent : 0) + ' Points');
    
    const rd = data && data.redeemed_at ? new Date(data.redeemed_at) : new Date();
    const rdStr = rd.toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' });
    const vu = new Date(rd);
    vu.setDate(vu.getDate() + 30);
    const vuStr = vu.toLocaleDateString('en-US', { year:'numeric', month:'long', day:'numeric', hour:'2-digit', minute:'2-digit' });
    
    $('#ticketRedeemedDate').text(rdStr);
    $('#ticketValidUntil').text(vuStr);
    $('#ticketReference').text(data ? data.reference : '');
    
    $('#rewardTicketModal').css('display','flex');
}

function closeRewardTicket() { 
    $('#rewardTicketModal').hide(); 
}

function printRewardTicket() { 
    showToast('Use browser Print', 'info'); 
}

function downloadRewardTicket() { 
    showToast('Use Print or Save as PDF', 'info'); 
}

// ===== MOBILE FUNCTIONS =====
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const icon = document.querySelector('#mobileMenuToggle i');
    
    if (!menu) return;
    
    if (mobileMenuOpen) {
        menu.classList.remove('show');
        icon.className = 'fas fa-bars';
    } else {
        menu.classList.add('show');
        icon.className = 'fas fa-times';
        if (searchOverlayOpen) toggleSearchOverlay();
    }
    mobileMenuOpen = !mobileMenuOpen;
}

function closeMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    const icon = document.querySelector('#mobileMenuToggle i');
    if (menu && mobileMenuOpen) {
        menu.classList.remove('show');
        icon.className = 'fas fa-bars';
        mobileMenuOpen = false;
    }
}

function toggleSearchOverlay() {
    const overlay = document.getElementById('searchOverlay');
    if (!overlay) return;
    
    if (searchOverlayOpen) {
        overlay.classList.remove('show');
    } else {
        overlay.classList.add('show');
        document.getElementById('mobileMovieSearch').focus();
        if (mobileMenuOpen) toggleMobileMenu();
    }
    searchOverlayOpen = !searchOverlayOpen;
}

// ===== MOBILE SEARCH =====
function performMobileSearch() {
    let searchTerm = $('#mobileMovieSearch').val().toLowerCase().trim();
    
    if ($('#home').is(':visible')) {
        if (searchTerm === '') {
            $('#homeMoviesGrid .movie-card').show();
        } else {
            $('#homeMoviesGrid .movie-card').each(function() {
                let title = $(this).find('h3').text().toLowerCase();
                let genre = $(this).find('p').first().text().toLowerCase();
                $(this).toggle(title.includes(searchTerm) || genre.includes(searchTerm));
            });
        }
    } else if ($('#comingSoon').is(':visible')) {
        if (searchTerm === '') {
            $('#comingSoonGrid .movie-card').show();
        } else {
            $('#comingSoonGrid .movie-card').each(function() {
                let title = $(this).find('h3').text().toLowerCase();
                let genre = $(this).find('p').first().text().toLowerCase();
                $(this).toggle(title.includes(searchTerm) || genre.includes(searchTerm));
            });
        }
    } else if ($('#allMovies').is(':visible')) {
        if (searchTerm === '') {
            $('#allMoviesGrid .movie-card').show();
        } else {
            $('#allMoviesGrid .movie-card').each(function() {
                let title = $(this).find('h3').text().toLowerCase();
                let genre = $(this).find('p').first().text().toLowerCase();
                $(this).toggle(title.includes(searchTerm) || genre.includes(searchTerm));
            });
        }
    }
}

// ===== THEME TOGGLE =====
function initializeTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
    
    $('#floatingThemeToggle i').attr('class', savedTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun');
    
    $('#floatingThemeToggle').off('click').on('click', function() {
        toggleTheme();
    });
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    document.documentElement.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
    
    $('#floatingThemeToggle i').attr('class', newTheme === 'dark' ? 'fas fa-moon' : 'fas fa-sun');
    
    showToast(`Switched to ${newTheme} mode`, 'success');
}

// ===== MODAL CLOSE =====
$('#closeModal, #movieDetailsModal').click(function(e) {
    if (e.target === this || e.target.id === 'closeModal') $('#movieDetailsModal').fadeOut();
});

$('#closeRewardTicket, #rewardTicketModal').click(function(e) {
    if (e.target === this || e.target.id === 'closeRewardTicket') $('#rewardTicketModal').hide();
});

$(document).keyup(function(e) { 
    if (e.key === 'Escape') { 
        $('#movieDetailsModal').fadeOut(); 
        $('#rewardTicketModal').hide(); 
    } 
});

// ===== FAQ =====
$(document).on('click', '.home-faq-question', function() {
    const currentFaq = $(this).parent();
    
    if (currentFaq.hasClass('active')) {
        currentFaq.removeClass('active');
    } else {
        $('.home-faq-item').removeClass('active');
        currentFaq.addClass('active');
    }
});

// ===== ZOOM FUNCTIONALITY =====
let currentZoom = 1;
const ZOOM_STEP = 0.2;
const MIN_ZOOM = 0.5;
const MAX_ZOOM = 3;

// Create zoom controls and append to booking screen
function initZoomControls() {
    // Check if controls already exist
    if ($('#zoomControls').length) return;
    
    const zoomControls = $(`
        <div id="zoomControls" class="zoom-controls">
            <button type="button" class="zoom-btn" id="zoomOut"><i class="fas fa-minus"></i></button>
            <span class="zoom-level" id="zoomLevel">100%</span>
            <button type="button" class="zoom-btn" id="zoomIn"><i class="fas fa-plus"></i></button>
        </div>
    `);
    
    // Insert after seat layout container but before seat legend
    $('.seat-layout-container').after(zoomControls);
    
    // Bind zoom events with preventDefault
    $('#zoomIn').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zoomIn();
        return false;
    });
    
    $('#zoomOut').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        zoomOut();
        return false;
    });
    
    // Add pinch zoom support
    let initialDistance = 0;
    let initialZoom = currentZoom;
    
    $('.seat-layout-container')[0].addEventListener('touchstart', function(e) {
        if (e.touches.length === 2) {
            e.preventDefault();
            const dx = e.touches[0].clientX - e.touches[1].clientX;
            const dy = e.touches[0].clientY - e.touches[1].clientY;
            initialDistance = Math.sqrt(dx * dx + dy * dy);
            initialZoom = currentZoom;
        }
    }, { passive: false });
    
    $('.seat-layout-container')[0].addEventListener('touchmove', function(e) {
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
    
    $('.seat-layout-container')[0].addEventListener('touchend', function(e) {
        if (e.touches.length < 2) {
            initialDistance = 0;
        }
    });
}

function setZoom(zoom) {
    currentZoom = zoom;
    $('.seat-layout').css('transform', `scale(${currentZoom})`);
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

// Override the showMovieBooking function to include zoom controls
const originalShowMovieBooking = showMovieBooking;
showMovieBooking = function(title, price, showDate, showTime) {
    originalShowMovieBooking(title, price, showDate, showTime);
    
    // Initialize zoom controls after seat grid is populated
    setTimeout(() => {
        initZoomControls();
        resetZoom(); // Reset zoom to 1x when new booking screen loads
    }, 100);
}

// Also reset zoom when going back from payment
const originalGoBackFromPayment = goBackFromPayment;
goBackFromPayment = function() {
    originalGoBackFromPayment();
    setTimeout(() => {
        resetZoom();
    }, 100);
}

const originalGoBackFromBooking = goBackFromBooking;
goBackFromBooking = function() {
    originalGoBackFromBooking();
    setTimeout(() => {
        resetZoom();
    }, 100);
}

// ===== DATABASE LOADING =====
function loadFromDatabase() {
    // Load movies
    $.ajax({
        url: 'api.php?action=get_movies',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('Movies loaded from API:', data);
            
            if (data && Array.isArray(data)) {
                // Clear existing arrays
                allMovies = [];
                allComingSoonMovies = [];
                
                // Log each movie's is_coming_soon value
                data.forEach(m => {
                    console.log(`Movie: ${m.title}, is_coming_soon: ${m.is_coming_soon}, type: ${typeof m.is_coming_soon}`);
                    
                    // Check if it's coming soon (value can be 1, "1", or true)
                    // Convert to number for comparison
                    const isComingSoon = Number(m.is_coming_soon) === 1;
                    
                    if (isComingSoon) {
                        allComingSoonMovies.push(m);
                        console.log(`  → Added to COMING SOON`);
                    } else {
                        allMovies.push(m);
                        console.log(`  → Added to NOW SHOWING`);
                    }
                });
                
                console.log('Now showing:', allMovies);
                console.log('Coming soon:', allComingSoonMovies);
                
                renderMovies();
                renderComingSoon();
            } else {
                console.error('Invalid data format:', data);
                showToast('Error loading movies', 'error');
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error loading movies:', {
                status: textStatus,
                error: errorThrown,
                response: jqXHR.responseText
            });
            showToast('Error loading movies', 'error');
        }
    });
    
    // Load user points
    $.getJSON('api.php?action=get_user_points&user_id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>', function(data) {
        if (data && data.points !== undefined) {
            userPoints = parseInt(data.points);
            $('#userPoints').text(userPoints);
            console.log('User points loaded:', userPoints);
            
            // After points are loaded, update displays
            setTimeout(() => {
                renderPromos();
                updatePointsDisplay();
            }, 100);
        }
    }).fail(function(jqXHR) {
        console.error('Error loading points:', jqXHR.responseText);
    });
    
    // Load bookings
    $.getJSON('api.php?action=get_bookings&user_id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>', function(data) {
        if (data && data.length > 0) {
            userBookings = data;
            
            // Calculate total points from bookings
            let totalPointsFromBookings = 0;
            userBookings.forEach(booking => {
                totalPointsFromBookings += parseInt(booking.points_earned || 0);
            });
            
            console.log('Bookings loaded:', userBookings.length, 'bookings');
            console.log('Total points from bookings:', totalPointsFromBookings);
            
            renderBookings();
            
            // Update displays after bookings are loaded
            setTimeout(() => {
                renderPromos();
                updatePointsDisplay();
            }, 100);
        } else {
            console.log('No bookings found for user');
            userBookings = [];
            renderBookings();
        }
    }).fail(function(jqXHR) {
        console.error('Error loading bookings:', jqXHR.responseText);
        userBookings = [];
        renderBookings();
    });
    
    // Load promos
    $.getJSON('api.php?action=get_promos', function(data) {
        if (data && data.length > 0) {
            promos = data;
            console.log('Promos loaded:', promos.length, 'promos');
            
            // Log each promo's points for debugging
            promos.forEach(p => {
                console.log(`Promo: ${p.title}, Points Required: ${p.points_required || p.points || 0}`);
            });
            
            renderPromos();
        } else {
            console.log('No promos found');
            promos = [];
            renderPromos();
        }
    }).fail(function(jqXHR) {
        console.error('Error loading promos:', jqXHR.responseText);
        promos = [];
        renderPromos();
    });
    
    // Load redeemed promos
    $.getJSON('api.php?action=get_redeemed&user_id=<?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>', function(data) {
        if (data && data.length > 0) {
            let redeemed = {};
            let totalPointsSpent = 0;
            
            data.forEach(item => {
                let ticket = JSON.parse(item.ticket_data || '{}');
                redeemed[item.promo_id] = {
                    points_spent: parseInt(item.points_spent || 0),
                    redeemed_at: item.redeemed_at,
                    ticket_data: ticket
                };
                totalPointsSpent += parseInt(item.points_spent || 0);
            });
            
            userRedeemedPromos = redeemed;
            console.log('Redeemed promos loaded:', Object.keys(redeemed).length, 'promos');
            console.log('Total points spent on promos:', totalPointsSpent);
            
            renderPromos();
        } else {
            console.log('No redeemed promos found');
            userRedeemedPromos = {};
            renderPromos();
        }
    }).fail(function(jqXHR) {
        console.error('Error loading redeemed promos:', jqXHR.responseText);
        userRedeemedPromos = {};
        renderPromos();
    });
}

// ===== TRAILER FUNCTIONS =====
function playTrailer(videoId, title) {
    console.log('Playing trailer - Original videoId:', videoId); // Debug log
    console.log('Title:', title);
    
    // If videoId is empty or undefined
    if (!videoId) {
        showToast('No trailer available', 'error');
        return;
    }
    
    // Convert videoId to string in case it's a number
    videoId = String(videoId).trim();
    
    // If it's a full YouTube URL, extract the ID
    if (videoId.includes('youtube.com/watch?v=')) {
        const match = videoId.match(/v=([^&]+)/);
        if (match && match[1]) {
            videoId = match[1];
        }
    } else if (videoId.includes('youtu.be/')) {
        const match = videoId.match(/youtu\.be\/([^?]+)/);
        if (match && match[1]) {
            videoId = match[1];
        }
    }
    
    // Remove any extra characters
    videoId = videoId.replace(/[^a-zA-Z0-9_-]/g, '');
    
    console.log('Final videoId after processing:', videoId);
    
    if (!videoId || videoId.length < 5) {
        showToast('Invalid trailer URL', 'error');
        return;
    }
    
    const embedUrl = `https://www.youtube.com/embed/${videoId}?autoplay=1&rel=0&modestbranding=1`;
    console.log('Embed URL:', embedUrl);
    
    const iframe = $(`
        <iframe 
            src="${embedUrl}"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            allowfullscreen>
        </iframe>
    `);
    
    $('#trailerVideoContainer').empty().append(iframe);
    $('#trailerModal').css('display', 'flex');
    $('body').css('overflow', 'hidden');
}

function closeTrailerModal() {
    $('#trailerModal').hide();
    $('#trailerVideoContainer').empty();
    $('body').css('overflow', '');
}

function handleModalClick(event) {
    if (event.target.id === 'trailerModal') {
        closeTrailerModal();
    }
}

function playCurrentMovieTrailer() {
    if (currentMovieTrailer) {
        let videoId = currentMovieTrailer.videoUrl;
        
        // If it's a full YouTube URL, extract the ID
        if (videoId.includes('youtube.com/watch?v=')) {
            videoId = videoId.split('v=')[1]?.split('&')[0];
        } else if (videoId.includes('youtu.be/')) {
            videoId = videoId.split('youtu.be/')[1]?.split('?')[0];
        }
        
        if (videoId) {
            playTrailer(videoId, currentMovieTrailer.title);
        } else {
            showToast('Invalid trailer URL', 'error');
        }
    } else {
        showToast('Trailer not available for this movie', 'info');
    }
}

// Load occupied seats when needed
const originalShowMovieBookingForLoad = showMovieBooking;
showMovieBooking = function(title, price, showDate, showTime) {
    // Load occupied seats from database
    $.getJSON(`api.php?action=get_occupied_seats&movie=${encodeURIComponent(title)}&date=${showDate}&time=${encodeURIComponent(showTime)}`, function(response) {
        let key = title + '|' + showDate + '|' + showTime;
        
        // Handle both array and object responses
        if (Array.isArray(response)) {
            // Old format - just an array of seats
            occupiedSeatsData[key] = response;
        } else if (response && response.occupied_seats) {
            // New format - object with occupied_seats array
            occupiedSeatsData[key] = response.occupied_seats;
        } else {
            occupiedSeatsData[key] = [];
        }
        
        console.log('Occupied seats for', key, ':', occupiedSeatsData[key]);
        
        // Call original function
        originalShowMovieBookingForLoad(title, price, showDate, showTime);
    }).fail(function(xhr, status, error) {
        console.error('Error loading occupied seats:', error);
        let key = title + '|' + showDate + '|' + showTime;
        occupiedSeatsData[key] = [];
        originalShowMovieBookingForLoad(title, price, showDate, showTime);
    });
}

// ===== DEBUG FUNCTION =====
function testMovieAPI() {
    console.log('Testing movie API...');
    $.ajax({
        url: 'api.php?action=get_movies', 
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            console.log('API Response:', data);
            alert('API Response: ' + JSON.stringify(data, null, 2));
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('API Error:', {
                status: textStatus,
                error: errorThrown,
                response: jqXHR.responseText
            });
            alert('API Error: ' + jqXHR.responseText);
        }
    });
}

// ===== PWA INSTALLATION DETECTION (SINGLE SCRIPT) =====
if ('serviceWorker' in navigator) {
  window.addEventListener('load', function() {
    navigator.serviceWorker.register('/sw.js').then(function(registration) {
      console.log('✅ ServiceWorker registered successfully');
      
      // Check if already installed
      if (window.matchMedia('(display-mode: standalone)').matches) {
        console.log('📱 CineBook is running in standalone mode - no browser UI');
        document.body.classList.add('pwa-mode');
      }
      
    }).catch(function(err) {
      console.log('❌ ServiceWorker registration failed: ', err);
    });
  });
}

// Listen for display mode changes
window.matchMedia('(display-mode: standalone)').addEventListener('change', (evt) => {
  if (evt.matches) {
    console.log('📱 Now running in standalone mode');
    document.body.classList.add('pwa-mode');
  }
});

// Check if page is loaded from home screen (iOS)
if (window.navigator.standalone === true) {
  console.log('📱 Running in standalone mode (iOS)');
  document.body.classList.add('pwa-mode');
}
</script>

</body>
</html>
