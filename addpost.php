<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('location:login.php');
}

if (isset($_SESSION['error'])) {
    echo "<script>alert('" . $_SESSION['error'] . "');</script>";
    unset($_SESSION['error']);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Link to Main CSS file -->
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <title>Add Post</title>
</head>

<body>
    <div class="container">
        <a href="admin.php" class="btn btn-secondary mb-3">Back to Admin</a>
        <h2>Add Post</h2>

        <!-- Form to submit the Quill content -->
        <form method="POST" action="addpost_script.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="title">Post Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="Enter title">
            </div>

            <div class="form-group">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-select">
                    <option selected disabled>Select Category</option>
                    <option value="Astrology">Astrology</option>
                    <option value="Business">Business</option>
                    <option value="Entertainment">Entertainment</option>
                    <option value="Technology">Technology</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tags">Tags</label>
                <select class="form-multi-select" id="ms1" multiple data-placeholder="Select tags..." name="tags[]">
                    <option disabled>First Select Category</option>
                </select>
            </div>

            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <input type="text" name="meta_description" id="meta_description" class="form-control" placeholder="Meta description">
            </div>

            <div class="form-group">
                <label for="meta_keywords">Meta Keywords</label>
                <input type="text" name="meta_keywords" id="meta_keywords" class="form-control" placeholder="Enter meta keywords">
            </div>

            <div class="form-group">
                <label for="thumb_image">Thumbnail Image</label>
                <input type="file" name="thumb_image" id="thumb_image" class="form-control">
            </div>

            <div class="form-group">
                <label for="editor-container">Post Content</label>
                <div id="editor-container"></div>
                <input type="hidden" name="content" id="content"> <!-- Hidden field to store Quill content -->
            </div>

            <button type="submit" class="btn btn-primary">Save Post</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Quill
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': '1' }, { 'header': '2' }, { 'font': [] }],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    [{ 'align': [] }],
                    ['bold', 'italic', 'underline'],
                ]
            }
        });

        // Save the content (before submitting the form)
        document.querySelector('form').onsubmit = function () {
            var content = quill.root.innerHTML;
            document.getElementById('content').value = content;
        };

        $(document).ready(function () {

            // Initialize Select2 for multi-select
            $('#ms1').select2();

            function populateTags(category) {
                $('#ms1').empty(); // Empty the current options in the multi-select
                // Add new options based on selected category
                if (category === 'Astrology') {
                    $('#ms1').append('<option value="Zodiac Signs">Zodiac Signs</option>');
                    $('#ms1').append('<option value="Horoscopes">Horoscopes</option>');
                    $('#ms1').append('<option value="Birth Charts">Birth Charts</option>');
                    $('#ms1').append('<option value="Astrology Predictions">Astrology Predictions</option>');
                    $('#ms1').append('<option value="Tarot Reading">Tarot Reading</option>');
                    $('#ms1').append('<option value="Astrology Compatibility">Astrology Compatibility</option>');
                    $('#ms1').append('<option value="Moon Phases">Moon Phases</option>');
                    $('#ms1').append('<option value="Tarot Cards">Tarot Cards</option>');
                    $('#ms1').append('<option value="Astrology Forecasts">Astrology Forecasts</option>');
                    $('#ms1').append('<option value="Numerology">Numerology</option>');
                    $('#ms1').append('<option value="Daily Horoscope">Daily Horoscope</option>');
                    $('#ms1').append('<option value="Psychic Readings">Psychic Readings</option>');
                    $('#ms1').append('<option value="Astrology Love Match">Astrology Love Match</option>');
                    $('#ms1').append('<option value="Astrology 2025">Astrology 2025</option>');
                    $('#ms1').append('<option value="Planetary Movements">Planetary Movements</option>');
                } else if (category === 'Business') {
                    $('#ms1').append('<option value="Entrepreneurship">Entrepreneurship</option>');
                    $('#ms1').append('<option value="Startups">Startups</option>');
                    $('#ms1').append('<option value="Business Strategy">Business Strategy</option>');
                    $('#ms1').append('<option value="Marketing Tips">Marketing Tips</option>');
                    $('#ms1').append('<option value="Leadership Skills">Leadership Skills</option>');
                    $('#ms1').append('<option value="Small Business">Small Business</option>');
                    $('#ms1').append('<option value="Digital Marketing">Digital Marketing</option>');
                    $('#ms1').append('<option value="Sales Techniques">Sales Techniques</option>');
                    $('#ms1').append('<option value="Business Growth">Business Growth</option>');
                    $('#ms1').append('<option value="E-commerce Trends">E-commerce Trends</option>');
                    $('#ms1').append('<option value="Investment Tips">Investment Tips</option>');
                    $('#ms1').append('<option value="Financial Planning">Financial Planning</option>');
                    $('#ms1').append('<option value="Remote Work">Remote Work</option>');
                    $('#ms1').append('<option value="Startup Funding">Startup Funding</option>');
                    $('#ms1').append('<option value="Business Networking">Business Networking</option>');
                } else if (category === 'Entertainment') {
                    $('#ms1').append('<option value="Movie Reviews">Movie Reviews</option>');
                    $('#ms1').append('<option value="Celebrity News">Celebrity News</option>');
                    $('#ms1').append('<option value="TV Shows">TV Shows</option>');
                    $('#ms1').append('<option value="Film Releases">Film Releases</option>');
                    $('#ms1').append('<option value="Music Industry">Music Industry</option>');
                    $('#ms1').append('<option value="Streaming Services">Streaming Services</option>');
                    $('#ms1').append('<option value="Pop Culture">Pop Culture</option>');
                    $('#ms1').append('<option value="Red Carpet Events">Red Carpet Events</option>');
                    $('#ms1').append('<option value="Celebrity Interviews">Celebrity Interviews</option>');
                    $('#ms1').append('<option value="Concerts and Festivals">Concerts and Festivals</option>');
                    $('#ms1').append('<option value="Entertainment News">Entertainment News</option>');
                    $('#ms1').append('<option value="Video Games">Video Games</option>');
                    $('#ms1').append('<option value="Book Reviews">Book Reviews</option>');
                    $('#ms1').append('<option value="Celebrity Gossip">Celebrity Gossip</option>');
                    $('#ms1').append('<option value="Viral Trends">Viral Trends</option>');
                } else if (category === 'Technology') {
                    $('#ms1').append('<option value="Artificial Intelligence">Artificial Intelligence</option>');
                    $('#ms1').append('<option value="Software Development">Software Development</option>');
                    $('#ms1').append('<option value="Coding Tutorials">Coding Tutorials</option>');
                    $('#ms1').append('<option value="Tech News">Tech News</option>');
                    $('#ms1').append('<option value="Gadgets and Reviews">Gadgets and Reviews</option>');
                    $('#ms1').append('<option value="Virtual Reality">Virtual Reality</option>');
                    $('#ms1').append('<option value="Blockchain Technology">Blockchain Technology</option>');
                    $('#ms1').append('<option value="Cybersecurity Tips">Cybersecurity Tips</option>');
                    $('#ms1').append('<option value="Internet of Things">Internet of Things</option>');
                    $('#ms1').append('<option value="Cloud Computing">Cloud Computing</option>');
                    $('#ms1').append('<option value="Smart Devices">Smart Devices</option>');
                    $('#ms1').append('<option value="Mobile Apps">Mobile Apps</option>');
                    $('#ms1').append('<option value="Programming Languages">Programming Languages</option>');
                    $('#ms1').append('<option value="Technology Trends">Technology Trends</option>');
                    $('#ms1').append('<option value="Tech Startups">Tech Startups</option>');

                }

                // Reinitialize Select2 after adding new options
                $('#ms1').select2();
            }

            $('#category').on('change', function () {
                let category = $(this).val();
                populateTags(category);
            });
        });
    </script>
</body>

</html>