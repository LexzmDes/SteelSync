<?php include 'includes/style.php'; ?>
<?php include 'includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intellitech Systems</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #ff6b00;
            --text-dark: #333;
            --text-light: #666;
            --bg-light: #f7f7f7;
            --white: #ffffff;
            --shadow-subtle: 0 8px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        * {
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Arial', sans-serif;
            background-color: var(--bg-light);
            line-height: 1.6;
            color: var(--text-dark);
        }

        .services-container {
            max-width: 1300px;
            margin: 0 auto;
            padding: 3rem 1.5rem;
        }

        .services-header {
            text-align: center;
            margin-bottom: 4rem;
            position: relative;
            overflow: hidden;
        }

        .services-header::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 50%;
            transform: translateX(-50%);
            width: 200px;
            height: 200px;
            background-color: rgba(255, 107, 0, 0.05);
            border-radius: 50%;
            z-index: -1;
        }

        .services-header h1 {
            font-size: 2.8rem;
            font-weight: 800;
            letter-spacing: -1px;
            margin-bottom: 1rem;
        }

        .services-header h1 span {
            color: var(--primary-color);
        }

        .services-header p {
            color: var(--text-light);
            max-width: 700px;
            margin: 0 auto;
            font-size: 1.1rem;
            font-weight: 300;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.5rem;
        }

        .service-card {
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-subtle);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .service-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background-color: var(--primary-color);
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        .service-card:hover::after {
            transform: scaleX(1);
            transform-origin: left;
        }

        .service-card:hover {
            transform: translateY(-15px);
            box-shadow: var(--shadow-hover);
        }

        .service-image {
            height: 280px;
            background-size: cover;
            background-position: center;
            position: relative;
            filter: grayscale(20%) brightness(0.9);
            transition: filter 0.3s ease;
        }

        .service-card:hover .service-image {
            filter: grayscale(0%) brightness(1);
        }

        .service-icon {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #ff6600;
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .service-card:hover .service-icon {
            transform: rotate(15deg) scale(1.1);
        }

        .service-icon i {
            font-size: 1.8rem;
        }

        .service-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .service-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .service-title {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            transition: color 0.3s ease;
        }

        .service-card:hover .service-title {
            color: var(--primary-color);
        }

        .service-explore {
            color: var(--primary-color);
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 1px;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s ease;
        }

        .service-card:hover .service-explore {
            opacity: 1;
            transform: translateX(0);
        }

        .service-description {
            color: var(--text-light);
            margin-top: auto;
            opacity: 0.8;
            transition: opacity 0.3s ease;
            margin-bottom: 1rem;
        }

        .service-card:hover .service-description {
            opacity: 1;
        }

        /* Price Tag Styling */
        .service-price {
            color: #ff6600;
            font-weight: 700;
            font-size: 1.1rem;
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px dashed rgba(255, 102, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .service-card:hover .service-price {
            transform: scale(1.05);
        }

        @media (max-width: 768px) {
            .services-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="services-container">
        <div class="services-header">
            <h1>Our <span>Services</span></h1>
            <p>Innovative Security and Automation Solutions</p>
        </div>
        <div class="services-grid" id="servicesGrid"></div>
    </div>

    <script>
        const services = [{
                image: 'images/gate.jpeg',
                icon: 'fa-solid fa-lock',
                title: 'Gate Automation',
                description: 'Advanced automated gate systems with smart access control and seamless integration.',
                price: 'Price Starts at  ₱90,000',
                link: 'services/gate automation.php'
            },
            {
                image: 'images/fabrication.jpeg',
                icon: 'fa-solid fa-home',
                title: 'Gate Fabrication',
                description: 'Custom-designed gates crafted with precision and aesthetic appeal.',
                price: 'Price Starts at  ₱220,000',
                link: 'services/gate_fabrication.php'
            },
            {
                image: 'images/sectional.jpeg',
                icon: 'fa-solid fa-camera',
                title: 'Sectional Garage Door',
                description: 'Modern garage doors offering superior insulation and security.',
                price: 'Price Starts at  ₱230,000',
                link: 'services/garage_door.php'
            },
            {
                image: 'images/11.jpg',
                icon: 'fa-solid fa-bolt',
                title: 'Roll Up Door',
                description: 'High-performance doors for commercial and industrial applications.',
                price: 'Price Starts at  ₱220,000',
                link: 'services/roll_up_door.php'
            },
            {
                image: 'images/electric.jpeg',
                icon: 'fa-solid fa-shield-halved',
                title: 'Smart Electric Fence',
                description: 'Cutting-edge perimeter protection with advanced monitoring systems.',
                price: 'Price Starts at ₱70,000',
                link: 'services/electric_fence.php'
            },
            {
                image: 'images/11.jpg',
                icon: 'fas fa-home',
                title: 'Smart Curtain',
                description: 'Automated curtain systems with intelligent home integration.',
                price: 'Price Starts at  ₱70,000',
                link: 'services/smart_curtain.php'
            }
        ];

        function renderServices() {
            const servicesGrid = document.getElementById('servicesGrid');
            servicesGrid.innerHTML = '';

            services.forEach(service => {
                const serviceCard = document.createElement('div');
                serviceCard.classList.add('service-card');
                serviceCard.innerHTML = `
            <div class="service-image" style="background-image: url('${service.image}')">
                <div class="service-icon">
                    <i class="${service.icon}"></i>
                </div>
            </div>
            <div class="service-content">
                <div class="service-header">
                    <h3 class="service-title">${service.title}</h3>
                    <span class="service-explore">Explore</span>
                </div>
                <p class="service-description">${service.description}</p>
                <div class="service-price">
                    <i class="fa-solid fa-tag"></i> ${service.price}
                </div>
            </div>
        `;
                serviceCard.addEventListener('click', () => {
                    window.location.href = service.link;
                });
                serviceCard.style.cursor = 'pointer';
                servicesGrid.appendChild(serviceCard);
            });
        }
        document.addEventListener('DOMContentLoaded', renderServices);
    </script>
    <?php include 'includes/footer.php'; ?>

    <?php include "includes/main.php"; ?>
</body>

</html>