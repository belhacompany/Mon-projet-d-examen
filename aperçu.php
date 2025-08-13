<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Aperçu des Livres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: 25% 0.0075 70;

            --pink: 77.75% 0.1003 350.51;
            --gold: 84.16% 0.1169 71.19;
            --mint: 84.12% 0.1334 165.28;

            --mobile--w: 360px;
            --mobile--h: 540px;

            --outline-w: 9px;
            --preview-bg: #fff;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(
                50deg,
                oklch(from oklch(var(--bg)) 50% c h),
                oklch(from oklch(var(--bg)) 90% c h)
            );
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2dvw;
            overflow: hidden;
            color-scheme: dark;
        }

        .font-serif-display {
            font-family: 'Playfair Display', serif;
        }

        .card-container {
            position: relative;
            display: block;
            width: 100%;
            max-width: 300px;
            aspect-ratio: 9 / 15.5;
            max-height: 90vh;
            border-radius: 1.75rem;
            overflow: hidden;
            background-size: cover;
            background-position: center;
            box-shadow:
                0 0 0 2px rgba(255, 214, 102, 0.4),
                0 0 25px 8px rgba(255, 214, 102, 0.15),
                0 20px 40px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            transform-style: preserve-3d;
            transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1),
                        box-shadow 0.4s ease-out,
                        background-size 1.5s ease-out;
            cursor: grab;
            will-change: transform, box-shadow, background-size;
            perspective: 1000px;
            transform: rotateX(5deg) rotateY(0deg);
        }

        .card-container:hover {
            box-shadow:
                0 0 0 3px rgba(255, 214, 102, 0.6),
                0 0 35px 12px rgba(255, 214, 102, 0.25),
                0 30px 60px rgba(0, 0, 0, 0.4),
                0 0 0 2px rgba(255, 255, 255, 0.2) inset;
            transform: rotateX(10deg) rotateY(5deg) translateZ(20px);
        }

        .card-container:active {
            cursor: grabbing;
            transform: rotateX(8deg) rotateY(3deg) translateZ(15px) scale(0.98);
        }

        .inner-border-overlay {
            position: absolute;
            inset: 14px;
            border-radius: 1.375rem;
            pointer-events: none;
            z-index: 10;
            box-shadow:
                inset 0.5px 0.5px 1.5px rgba(255, 235, 180, 0.6),
                inset -1px -1px 1px rgba(160, 110, 0, 0.5),
                inset 3px 3px 6px rgba(0, 0, 0, 0.25),
                0 0 20px rgba(255, 214, 102, 0.1);
            transform: translateZ(30px);
            will-change: transform;
            border: 1px solid rgba(255, 214, 102, 0.1);
        }

        .content-area {
            position: absolute;
            inset: 14px;
            border-radius: 1.375rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            z-index: 5;
            transform: translateZ(60px);
            will-change: transform;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.3));
        }

        .elevation-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(135deg, rgba(250, 204, 21, 0.95), rgba(245, 158, 11, 0.9));
            border-radius: 9999px;
            padding: 0.5rem 1rem;
            color: #422006;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.025em;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 
                0 4px 15px rgba(0, 0, 0, 0.3),
                0 0 0 1px rgba(255, 255, 255, 0.2) inset,
                0 2px 4px rgba(255, 255, 255, 0.1) inset;
            transform: translateZ(70px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            z-index: 25;
            will-change: transform;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateZ(70px) translateY(0px); }
            50% { transform: translateZ(70px) translateY(-5px); }
        }

        .elevation-badge svg {
            width: 1em;
            height: 1em;
            filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.3));
        }

        .gradient-overlay {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 75%;
            background: linear-gradient(to top, 
                rgba(10, 10, 10, 0.95) 0%, 
                rgba(10, 10, 10, 0.75) 30%,
                rgba(10, 10, 10, 0.45) 60%,
                transparent 100%);
            pointer-events: none;
            z-index: 15;
            transform: translateZ(5px);
            will-change: transform;
            backdrop-filter: blur(1px);
        }

        .text-block {
            position: relative;
            z-index: 20;
            color: #f8fafc;
            text-align: center;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.7),
                0 4px 8px rgba(0,0,0,0.5);
            transform: translateZ(25px);
            will-change: transform;
            filter: drop-shadow(0 5px 15px rgba(0, 0, 0, 0.4));
        }

        .text-block h1 {
            letter-spacing: 0.025em;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-shadow: 
                0 3px 6px rgba(0,0,0,0.8),
                0 6px 12px rgba(0,0,0,0.6);
        }

        .text-block p {
            color: #cbd5e1;
            text-shadow: 
                0 2px 4px rgba(0,0,0,0.6),
                0 4px 8px rgba(0,0,0,0.4);
        }

        .mountain-icon {
            width: 1.5em;
            height: 1.5em;
            fill: currentColor;
            opacity: 0.9;
            vertical-align: middle;
            margin-bottom: -0.1em;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5));
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from { filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5)) brightness(1); }
            to { filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.5)) brightness(1.2); }
        }

        .tour-button {
            position: relative;
            z-index: 20;
            background: linear-gradient(145deg, #fde047, #facc15);
            color: #422006;
            font-weight: 600;
            padding: 0.75rem 1.75rem;
            border-radius: 9999px;
            text-align: center;
            width: auto;
            min-width: 190px;
            max-width: 85%;
            margin-left: auto;
            margin-right: auto;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            border: none;
            box-shadow:
                inset 2px 2px 4px rgba(160, 110, 0, 0.6),
                inset -2px -2px 4px rgba(255, 245, 200, 0.5),
                0 4px 15px rgba(0,0,0,0.2),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            transform: translateZ(40px);
            will-change: transform, box-shadow, background;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            letter-spacing: 0.025em;
            margin-top: 1rem;
            backdrop-filter: blur(5px);
        }

        .tour-button:hover {
            background: linear-gradient(145deg, #feec80, #fde047);
            box-shadow:
                inset 2px 2px 5px rgba(160, 110, 0, 0.5),
                inset -2px -2px 5px rgba(255, 245, 200, 0.6),
                0 8px 25px rgba(0,0,0,0.3),
                0 0 0 2px rgba(255, 255, 255, 0.2) inset;
            transform: translateZ(40px) translateY(-3px) scale(1.02);
            color: #3f2810;
        }

        .tour-button:active {
            background: linear-gradient(145deg, #facc15, #eab308);
            box-shadow:
                inset -2px -2px 4px rgba(160, 110, 0, 0.6),
                inset 2px 2px 4px rgba(255, 245, 200, 0.5),
                0 2px 8px rgba(0,0,0,0.2);
            transform: translateZ(40px) translateY(-1px) scale(0.98);
        }

        .btn-retour {
            position: fixed;
            top: 30px;
            left: 30px;
            z-index: 200;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            padding: 10px 22px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 
                0 4px 15px rgba(0,0,0,0.3),
                0 0 0 1px rgba(255, 255, 255, 0.1) inset;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transform: translateZ(0);
        }

        .btn-retour:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-3px) translateZ(10px);
            box-shadow: 
                0 8px 25px rgba(0,0,0,0.4),
                0 0 0 2px rgba(255, 255, 255, 0.2) inset;
        }

        /* Nouveau design d'aperçu - conteneur de cartes */
        .card__container {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            place-items: center;
            place-content: center;
            width: 100%;
            max-width: 1300px;
        }

        /* Atténuer les autres cartes quand une est hover/focus */
        .card__container:has(.card:hover, .card:focus-within) .card:not(:hover, :focus) {
            opacity: 0.4;
        }

        /* Carte */
        .card {
            --bg-pos-y--start: 0;
            --bg-pos-y--end: 0;
            --bg-pos-y: var(--bg-pos-y--start);
            --delay: 0;
            --duration: 6s;
            --img: url(https://assets.codepen.io/2392/360__homepage--full.png);

            --shadow-blur: 24px;
            --shadow-color: oklch(var(--bg));

            background-clip: padding-box;
            background-image: var(--img);
            background-position-y: var(--bg-pos-y);
            background-repeat: no-repeat;
            background-size: cover;
            background-attachment: fixed;

            border: var(--outline-w) solid var(--border-color, transparent);
            border-radius: 6px;
            box-shadow: 0 0 var(--shadow-blur) 0 var(--shadow-color);

            transition-property: border, box-shadow, filter, outline-offset, opacity, rotate, scale, z-index;
            transition-duration: 0.15s, 0.15s, 0.6s, 0.6s, 0.3s, 0.3s, 0.3s, 0.15s;

            filter: grayscale(100%) sepia(5%);
            mix-blend-mode: multiply;
            opacity: 0.69;

            scale: 0.85;
            rotate: var(--rotation, -4deg);

            outline: var(--outline-w) solid var(--preview-bg);
            outline-offset: var(--outline-w);

            min-height: var(--mobile--h);
            height: 100%;

            min-width: var(--mobile--w);
            width: 100%;

            position: relative;

            animation-name: bg-scroll;
            animation-delay: var(--delay);
            animation-duration: var(--duration);
            animation-fill-mode: forwards;
        }

        .card:focus-within,
        .card:hover {
            --shadow-blur: 200px;
            --shadow-color: oklch(var(--gold));
            --border-color: var(--shadow-color);

            background-color: white;
            mix-blend-mode: initial;
            filter: none;
            opacity: 1;
            outline-offset: calc(var(--outline-w) / 2);
            scale: 1;
            rotate: 0deg;
            transition-property: border, box-shadow, filter, outline-offset, opacity, rotate, scale, z-index;
            transition-duration: 0.15s, 0.15s, 0.3s, 0.3s, 0.3s, 0.3s, 0.3s, 0.15s;
            z-index: 6;
        }

        .card:focus-within { --shadow-color: oklch(var(--pink)); z-index: 7; }
        .card:hover:focus { --shadow-color: oklch(var(--mint)); }

        .card.mobile { max-height: var(--mobile--h); max-width: var(--mobile--w); }

        .card:nth-of-type(2) {
            --bg-pos-y--end: calc(var(--mobile--h) * -1.025);
            --rotation: 3deg;
        }
        .card:nth-of-type(3) {
            --bg-pos-y--end: calc(var(--mobile--h) * -2.25);
            --duration: 6.5s;
            --rotation: -1deg;
        }
        .card:nth-of-type(4) {
            --bg-pos-y--end: calc(var(--mobile--h) * -3.75);
            --duration: 6.75s;
            --rotation: -5deg;
        }
        .card:nth-of-type(5) {
            --bg-pos-y--end: calc(var(--mobile--h) * -4.82);
            --duration: 7s;
            --rotation: -2deg;
        }
        .card:nth-of-type(6) {
            --bg-pos-y--end: calc(var(--mobile--h) * -5.85);
            --duration: 7.25s;
            --rotation: 2deg;
        }
        .card:nth-of-type(7) {
            --bg-pos-y--end: calc(var(--mobile--h) * -7.21);
            --duration: 7.5s;
            --rotation: 4deg;
        }

        @keyframes bg-scroll { to { background-position-y: var(--bg-pos-y--end); } }

        /* Overlay d'informations sur clic */
        .card.show-info {
            mix-blend-mode: initial;
            filter: none;
            opacity: 1;
            scale: 1;
            rotate: 0deg;
            z-index: 10;
        }

        .card__container:has(.card.show-info) .card:not(.show-info) {
            opacity: 0.25;
        }

        .card .info-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 1.25rem 1.25rem 1.5rem;
            background: linear-gradient(to top,
                rgba(0,0,0,0.85) 0%,
                rgba(0,0,0,0.65) 35%,
                rgba(0,0,0,0.3) 65%,
                transparent 100%);
            backdrop-filter: blur(1px);
            color: #f8fafc;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
            border-radius: 6px;
        }

        .card.show-info .info-overlay { opacity: 1; pointer-events: auto; }

        .info-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 34px;
            height: 34px;
            border-radius: 9999px;
            border: 1px solid rgba(255,255,255,0.35);
            background: rgba(0,0,0,0.35);
            color: #fff;
            display: grid;
            place-items: center;
            cursor: pointer;
            transition: background .2s ease, transform .1s ease;
        }
        .info-close:hover { background: rgba(255,255,255,0.15); }
        .info-close:active { transform: scale(0.96); }

        .info-content { position: relative; z-index: 2; }
        .info-title { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 1.35rem; margin-bottom: .25rem; }
        .info-meta { font-size: .95rem; color: #e5e7eb; opacity: .95; }
        .info-year { font-size: .9rem; color: #cbd5e1; opacity: .9; margin-top: .35rem; }
    </style>
</head>
<body>
    <a href="affichageacceuil.php" class="btn-retour">
 <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24" transform="" id="injected-svg">
<!-- Boxicons v3.0 https://boxicons.com | License  https://docs.boxicons.com/free -->
<path d="M12.71 16.29 8.41 12l4.3-4.29-1.42-1.42L5.59 12l5.7 5.71z"/>
<path d="M16.29 6.29 10.59 12l5.7 5.71 1.42-1.42-4.3-4.29 4.3-4.29z"/>
    </svg>
    </a>
    
    <article class="card__container">
        <?php
        include 'include/bd.php';
        $sql = "SELECT * FROM livre";
        $result = $conn->query($sql);
        if ($result->num_rows === 0) {
            echo "<p style='color: #f8fafc; text-align: center; width:100%'>Aucun livre enregistré pour le moment.</p>";
        } else {
            while ($livre = $result->fetch_assoc()) {
                $backgroundImage = !empty($livre['Couverture'])
                    ? "data:image/jpeg;base64," . $livre['Couverture']
                    : "https://static.wixstatic.com/media/3d9313_45b151504946477791c3add537ac398a~mv2.png";

                // Chaque livre devient une carte visuelle, accessible au clavier
                echo '<div class="card mobile" tabindex="0" style="--img:url(\'' . $backgroundImage . '\')" title="' . htmlspecialchars($livre['Titre']) . '">';
                echo '  <div class="info-overlay">';
                echo '    <button class="info-close" aria-label="Fermer">✕</button>';
                echo '    <div class="info-content">';
                echo '      <div class="info-title">' . htmlspecialchars($livre['Titre']) . '</div>';
                echo '      <div class="info-meta">' . htmlspecialchars($livre['Auteur']) . ' • ' . (isset($livre['Genre']) ? htmlspecialchars($livre['Genre']) : 'Genre non spécifié') . '</div>';
                echo '      <div class="info-year">Année: ' . htmlspecialchars($livre['Annee']) . '</div>';
                echo '    </div>';
                echo '  </div>';
                echo '</div>';
            }
        }
        $conn->close();
        ?>
    </article>

    <script>
        // Toggle info on click or Enter/Space
        document.addEventListener('click', function(e) {
            const card = e.target.closest('.card');
            const closeBtn = e.target.closest('.info-close');
            if (closeBtn) {
                const openCard = closeBtn.closest('.card');
                openCard?.classList.remove('show-info');
                return;
            }
            if (card) {
                // Fermer les autres
                document.querySelectorAll('.card.show-info').forEach(c => c.classList.remove('show-info'));
                // Ouvrir celle-ci
                card.classList.add('show-info');
            }
        });

        document.addEventListener('keydown', function(e) {
            const active = document.activeElement;
            if (active?.classList?.contains('card') && (e.key === 'Enter' || e.key === ' ')) {
                e.preventDefault();
                active.classList.toggle('show-info');
            }
            if (e.key === 'Escape') {
                document.querySelectorAll('.card.show-info').forEach(c => c.classList.remove('show-info'));
            }
        });
    </script>
</body>
</html>