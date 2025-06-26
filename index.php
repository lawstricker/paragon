
<?php
include 'auth.php';

$userName = isset($_SESSION['fullName']) ? htmlspecialchars($_SESSION['fullName']) : 'Guest';
$userEmail = isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paragon</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                padding: 1rem;
                text-align: center;
            }
            
            .container {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }
            
            .page-view, .course-menu {
                width: 100%;
                min-height: auto;
                padding: 0.5rem;
            }
            
            #pdfViewer {
                min-height: 400px;
            }
            
            .user-info {
                margin-top: 1rem;
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
            
            .welcome-message {
                margin-right: 0;
                margin-bottom: 0.5rem;
            }
            
            .toolkit-item {
                padding: 0.75rem 0.5rem;
            }
        }
        
        @media (max-width: 480px) {
            #pdfViewer {
                min-height: 300px;
            }
            
            .logo {
                font-size: 1.25rem;
            }
            
            .logout-btn {
                width: 100%;
            }
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: white;
        }
        .logo {
            color: #00b4b4;
            font-size: 1.5rem;
            font-weight: bold;
        }
        .logout-btn {
            background-color: #00b4b4;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        .container {
            display: flex;
            padding: 2rem;
            gap: 2rem;
            flex-wrap: wrap;
            box-sizing: border-box;
        }
        .page-view {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            min-height: 500px;
            min-width: 300px;
        }
        .course-menu {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 1rem;
        }
        .course-menu h2 {
            margin-top: 0;
        }
        .toolkit-list {
            list-style: none;
            padding: 0;
        }
        .toolkit-item {
            margin: 0.5rem 0;
            padding: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            color: #333;
            text-decoration: none;
        }
        .toolkit-item:hover {
            background-color: #f0f2f5;
            border-radius: 4px;
        }
        .toolkit-icon {
            width: 24px;
            height: 24px;
            margin-right: 0.5rem;
            fill: #00b4b4;
        }
        #pdfViewer {
            width: 100%;
            height: 100%;
            min-height: 500px;
            overflow: auto;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .page-view {
            flex: 1;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            min-height: 500px;
            position: relative;
            overflow: hidden;
        }
        .page-view::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }
        object#pdfViewer {
            pointer-events: auto;
            overflow: auto;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .welcome-message {
            color: #333;
            font-weight: bold;
            margin-right: 1rem;
        }
        
        .user-email {
            color: #333;
            font-size: 0.9rem;
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
</head>
<body>
    <header class="header">
        <div class="logo">Paragon</div>
        <div class="user-info">
            <span class="welcome-message">Welcome, <span id="userName"><?php echo $userName; ?></span>!</span>
            <span class="user-email" id="userEmail"><?php echo $userEmail; ?></span>
            <button class="logout-btn">Logout</button>
        </div>
    </header>

    <style>
        .user-section {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .user-email {
            color: #333;
            font-size: 0.9rem;
        }
    </style>

    <script>

        

        function clearContainer() {
            const container = document.getElementById('pdf-container');
            while(container.firstChild) {
                container.removeChild(container.firstChild);
            }
        }

        function loadPDF(event, pdfPath) {
            event.preventDefault();
            clearContainer()
            // const pdfViewer = document.getElementById('pdfViewer');
            // pdfViewer.data = pdfPath;
            // const embedElement = pdfViewer.getElementsByTagName('embed')[0];
            // if (embedElement) {
            //     embedElement.src = pdfPath;
            // }

            const url = pdfPath;

            console.log('Loading PDF:', url);

            const container = document.getElementById('pdf-container');

            

            pdfjsLib.getDocument(url).promise.then(pdf => {
            const numPages = pdf.numPages;

            for(let pageNum = 1; pageNum <= numPages; pageNum++) {
                pdf.getPage(pageNum).then(page => {
                const scale = 1;
                const viewport = page.getViewport({ scale });

                // Create a canvas for each page
                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');
                canvas.height = viewport.height;
                canvas.width = viewport.width;

                // Append canvas to container
                container.appendChild(canvas);

                // Render page
                page.render({
                    canvasContext: context,
                    viewport: viewport
                });
                });
            }
            });
        }

        // Handle logout
        document.querySelector('.logout-btn').addEventListener('click', function() {
            window.location.href = 'logout.php'; // Redirect to logout page
        });

        // Disable right click
        //  document.addEventListener('contextmenu', function(e) {
        //     e.preventDefault();
        //    return false;
        //  });
    </script>
    
    <div class="container">
        <div class="page-view" style="height: 700px; overflow-y: scroll;">
            <div id="pdf-container"></div>

            <!-- <object id="pdfViewer" type="application/pdf" data="" style="width: 100%; height: 100%; min-height: 900px;">
                <param name="view" value="Fit" />
                <param name="toolbar" value="0" />
                <param name="navpanes" value="0" />
                <param name="scrollbar" value="1" />
                <param name="download" value="0" />
                <param name="printing" value="0" />
                <embed type="application/pdf" width="100%" height="100%" style="min-height: 900px;">
            </object> -->
        </div>
        
        <div class="course-menu">
            <h2>More on this course</h2>
            <div class="toolkit-list">
                <a href="Asset/TOOLKIT 1 - UNDERSTAND BRAND POSITIONING.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 1 - UNDERSTAND BRAND POSITIONING.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 1 - Understand Brand Positioning
                </a>
                <a href="Asset/TOOLKIT 2 - INTRODUCTION TO THE TOOLKIT.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 2 - INTRODUCTION TO THE TOOLKIT.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 2 - Introduction To The Toolkit
                </a>

                <a href="Asset/TOOLKIT 3 - ADVERTISING THAT WORKS.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 3 - ADVERTISING THAT WORKS.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 3 - Advertising That Works
                </a>

                <a href="Asset/TOOLKIT 4 - SAMPLE STRATEGIC MARKETING BLUEPRINT.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 4 - SAMPLE STRATEGIC MARKETING BLUEPRINT.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 4 - Sample Strategic Marketing Blueprint
                </a>

                <a href="Asset/TOOLKIT 5 - DIGITAL MARKETING – ensures you increase your word-of-mouth Marketing.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 5 - DIGITAL MARKETING – ensures you increase your word-of-mouth Marketing.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 5 - Digital Marketing – Ensures You Increase Your Word-Of-Mouth Marketing
                </a>

                <a href="Asset/TOOLKIT 6 - EFFECTIVE PR - The Power of Earned Media.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 6 - EFFECTIVE PR - The Power of Earned Media.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 6 - Effective Pr - The Power Of Earned Media
                </a>

                <a href="Asset/TOOLKIT 7 - SAMPLE PRESS RELEASE.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 7 - SAMPLE PRESS RELEASE.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 7 - Sample Press Release
                </a>

                <a href="Asset/TOOLKIT 8 - SALES PAGE WEBSITE.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 8 - SALES PAGE WEBSITE.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 8 - Sales Page Website
                </a>

                <a href="Asset/TOOLKIT 9 - SAMPLE SALES PAGE WEBSITE.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 9 - SAMPLE SALES PAGE WEBSITE.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 9 - Sample Sales Page Website
                </a>

                <a href="Asset/TOOLKIT 10 - SAMPLE BRAND MANUAL.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 10 - SAMPLE BRAND MANUAL.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 10 - Sample Brand Manual
                </a>


                <a href="Asset/TOOLKIT 11 - CONTACT US NOW TO GROW YOUR BRAND.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 11 - CONTACT US NOW TO GROW YOUR BRAND.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 11 - Contact Us Now To Grow Your Brand
                </a>

                <a href="Asset/TOOLKIT 12 - HUBSPOT SOCIAL MEDIA CONTENT CALENDAR USER GUIDE.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 12 - HUBSPOT SOCIAL MEDIA CONTENT CALENDAR USER GUIDE.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 12 - Hubspot Social Media Content Calendar User Guide
                </a>

                <a href="Asset/TOOLKIT 14 - MARKETING PLAN TEMPLATE.pdf" class="toolkit-item" onclick="loadPDF(event, 'Asset/TOOLKIT 14 - MARKETING PLAN TEMPLATE.pdf')">
                    <svg class="toolkit-icon" viewBox="0 0 24 24">
                        <path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20Z"/>
                    </svg>
                    Toolkit 14 - Martketing Plan Template
                </a>


                <!-- Add more toolkit items following the same pattern -->
            </div>
        </div>
    </div>
</body>
</html>