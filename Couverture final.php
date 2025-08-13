<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Oniversite FJKM Ravelojaona – L2 Informatique</title>

  <!-- GSAP + Plugins -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/MorphSVGPlugin.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Physics2DPlugin.min.js"></script>

  <!-- Mona Sans -->
  <link rel="stylesheet" href="https://fonts.cdnfonts.com/css/mona-sans">
</head>

<body>
  <div class="card">
    <img src="./logo info.jpg" alt="">
    <h2>Oniversite FJKM Ravelojaona</h2>
    <p>Projet Universitaire L2 Informatique</p>
    <button data-play-pause="toggle" class="play-pause-button" aria-label="Play / Pause" onclick="window.location.href='progress.php'">
      <svg viewBox="0 0 24 24" fill="none" class="play-pause-icon">
        <path data-play-pause="path"
              d="M3.5 5L3.50049 3.9468C3.50049 3.177 4.33382 2.69588 5.00049 3.08078L20.0005 11.741C20.6672 12.1259 20.6672 13.0882 20.0005 13.4731L17.2388 15.1412L17.0055 15.2759M3.50049 8L3.50049 21.2673C3.50049 22.0371 4.33382 22.5182 5.00049 22.1333L14.1192 16.9423L14.4074 16.7759"
              stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
      </svg>
    </button>
  </div>

  <div class="accents">
    <div class="acc-card"></div><div class="acc-card"></div><div class="acc-card"></div>
    <div class="light"></div><div class="light sm"></div>
    <div class="top-light"></div>
  </div>

  <style>
    *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
    html,body{height:100%;overflow:hidden}
    body{
      font-family:'Mona-Sans',sans-serif;
      background:linear-gradient(180deg,#343434 0%,#252525 100%);
      display:flex;align-items:center;justify-content:center;color:#ccc
    }
    .play-pause-button{
      background:none;border:1px solid #ffffff22;border-radius:50%;
      width:48px;height:48px;margin-top:16px;cursor:pointer;
      display:flex;align-items:center;justify-content:center;
      transition:background .2s
    }
    .play-pause-button:hover{background:#ffffff11}
    .play-pause-icon{width:24px;height:24px;color:#ccc}
    .card{
      position:absolute;width:320px;height:500px;
      background:linear-gradient(180deg,#292929aa 0%,#191919cc 50%);
      backdrop-filter:blur(4px);border-radius:16px;
      box-shadow:inset 0 2px 2px 0 #e7c4a088,inset 0 -2px 2px 0 #0003;
      padding:24px 42px 24px 24px;display:flex;flex-direction:column;justify-content:flex-end;z-index:2
    }
    .card img{position:absolute;top:32px;left:0;right:0;width:80%;margin:auto;pointer-events:none;user-select:none}
    .card h2{margin:8px 0;font-size:1.1em}
    .card p{margin:8px 0;font-size:.9em;font-weight:800;color:#aaa}
    .accents{position:absolute;left:0;right:0;top:20%;pointer-events:none;user-select:none}
    .acc-card{width:330px;height:500px;background:#eee1;border-radius:16px;position:absolute;left:0;right:0;top:20%;margin:auto;box-shadow:inset 0 2px 2px 0 #e0c9b266,inset 0 -2px 2px 0 #0004;backdrop-filter:blur(4px);transform-origin:20% 80%;animation:wobble 18s ease-in-out infinite}
    .acc-card:nth-child(2){animation-delay:-6s;animation-direction:reverse}
    .acc-card:nth-child(3){animation-delay:-18s;animation-duration:26s}
    .light{--bg:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 487 487'%3E%3Ccircle cx='243' cy='243.5' r='233' fill='none' stroke='%23aaa' stroke-width='18' stroke-opacity='.1'/%3E%3Ccircle cx='243.5' cy='243.5' r='243' fill='none' stroke='%23111'/%3E%3Ccircle cx='243' cy='243.5' r='222' fill='none' stroke='%23111'/%3E%3Cpath d='M10 243.5C10 114.8 114.3 10.5 243 10.5' fill='none' stroke='%23ddd' stroke-width='18'/%3E%3C/svg%3E");position:absolute;left:calc(0% + 300px);top:264px;width:164px;height:164px;z-index:-2;background:var(--bg);animation:rotate360 22s linear infinite}
    .light::before,.light::after{content:'';position:absolute;inset:0;background:var(--bg);filter:blur(3px);scale:1.01}
    .light::after{filter:blur(8px)}
    .light.sm{width:100px;height:100px;top:142px;left:calc(0% + 300px);animation-duration:18s;animation-delay:-10s}
    .top-light{position:absolute;left:0;right:0;top:-42px;width:284px;height:6px;border-radius:10px;background:#fffef9;box-shadow:0 0 1px 1px #ffc78e,0 1px 2px 1px #ff942977,0 2px 6px 1px #e98b2d77,0 4px 12px 0 #ff9e3d99,0 12px 20px 12px #ff800044}
    @keyframes rotate360{to{rotate:360deg}}
    @keyframes wobble{0%{transform:translateX(10px) translateY(20px) rotate(-3deg) scale(1)}20%{transform:translateX(-44px) translateY(-8px) rotate(6deg) scale(1.02)}60%{transform:translateX(32px) translateY(18px) rotate(-8deg) scale(1)}80%{transform:translateX(-42px) translateY(-22px) rotate(12deg) scale(.94)}100%{transform:translateX(10px) translateY(20px) rotate(-3deg) scale(1)}}
    .dot{position:absolute;width:10px;height:10px;border-radius:50%;pointer-events:none;z-index:9999}
  </style>

  <script>
    gsap.registerPlugin(MorphSVGPlugin, Physics2DPlugin);

    function initMorphingPlayPauseToggle() {
      const playPath = "M3.5 5L3.50049 3.9468C3.50049 3.177 4.33382 2.69588 5.00049 3.08078L20.0005 11.741C20.6672 12.1259 20.6672 13.0882 20.0005 13.4731L17.2388 15.1412L17.0055 15.2759M3.50049 8L3.50049 21.2673C3.50049 22.0371 4.33382 22.5182 5.00049 22.1333L14.1192 16.9423L14.4074 16.7759";
      const pausePath = "M15.5004 4.05859V5.0638V5.58691V8.58691V15.5869V19.5869V21.2549M8.5 3.96094V10.3721V17V19L8.5 21";

      const buttonToggle = document.querySelector('[data-play-pause="toggle"]');
      const iconPath = buttonToggle.querySelector('[data-play-pause="path"]');
      let isPlaying = false;

      buttonToggle.addEventListener("click", () => {
        gsap.to(iconPath, {
          duration: 0.5,
          morphSVG: { type: "rotational", map: "complexity", shape: isPlaying ? playPath : pausePath },
          ease: "power4.inOut"
        });
        isPlaying = !isPlaying;
      });
    }

    function initConfettiClick() {
      document.addEventListener("click", (event) => {
        const dotCount = gsap.utils.random(15, 30, 1);
        const colors = ["#00f5ff","#ff00ff","#39ff14"];

        for (let i = 0; i < dotCount; i++) {
          const dot = document.createElement("div");
          dot.classList.add("dot");
          document.body.appendChild(dot);

          gsap.set(dot, { backgroundColor: gsap.utils.random(colors), top: event.clientY, left: event.clientX, scale: 0 });

          gsap.timeline({ onComplete: () => dot.remove() })
              .to(dot, { scale: gsap.utils.random(0.3,1), duration: 0.3, ease: "power3.out" })
              .to(dot, {
                duration: 2,
                physics2D: {
                  velocity: gsap.utils.random(500,1000),
                  angle: gsap.utils.random(0,360),
                  gravity: 1500
                },
                autoAlpha: 0,
                ease: "none"
              }, "<");
        }
      });
    }

    document.addEventListener("DOMContentLoaded", () => {
      initMorphingPlayPauseToggle();
      initConfettiClick();
    });
  </script>
</body>
</html>