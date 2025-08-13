<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @import url('https://unpkg.com/normalize.css') layer(normalize);

@import url('https://fonts.googleapis.com/css2?family=Gloria+Hallelujah&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

@layer normalize, base, demo;

@layer demo {
  .arrow {
    display: inline-block;
    opacity: 0.6;
    position: fixed;
    font-size: 0.875rem;
    font-family: 'Gloria Hallelujah', cursive;
    transition: opacity 0.26s 0.26s ease-out;
    z-index: 1000;

    &.arrow--instruction {
      top: 50%;
      left: 50%;
      translate: -140% 150%;
      rotate: -10deg;
      svg {
        scale: 1 1;
        top: 40%;
        rotate: 10deg;
        left: 90%;
        width: 90px;
        translate: 0% 20%;
        position: absolute;
      }
    }
  }

  .actions {
    position: fixed;
    top: 200px;
    left: 118px;
    display: flex; 
    flex-direction: column;
    gap: 2rem;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(0px);
    padding: 106px 16px;
    border-radius: 254px;
    border: 1px solid rgba(53, 51, 51, 0.19);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    align-items: center;
}

  .hover {
    border: 0;
    background: transparent;
    position: relative;
    color: var(--color);
    padding: 0.8rem 1.5rem;
    background: var(--bg);
    cursor: pointer;
    outline-color: canvasText;
    border-radius: 0;
    font-size: 0.9rem;
    white-space: nowrap;
    transition: all 0.3s ease;

    &::after {
      content: '';
      background: white;
      position: absolute;
      inset: 0;
      mix-blend-mode: difference;
      scale: 0 1;
      transform-origin: 100% 50%;
      transition: scale 0.2s ease-out;
      pointer-events: none;
    }
  }

  .hover:is(:hover, :focus-visible)::after {
    scale: 1 1;
    transform-origin: 0 50%;
  }

  [data-intent='true'] .hover:is(:hover, :focus-visible)::after {
    transition: scale 0.2s 0.15s ease-out;
  }

  [data-vertical='true'] .hover::after {
    scale: 1 0;
    transform-origin: 50% 0;
  }
  [data-vertical='true'] .hover:is(:hover, :focus-visible)::after {
    scale: 1 1;
    transform-origin: 50% 100%;
  }

  [data-revert='true'] .hover::after,
  [data-revert='true'] .hover:is(:hover, :focus-visible)::after {
    transform-origin: 0 50%;
  }

  [data-vertical='true'][data-revert='true'] .hover::after,
  [data-vertical='true'][data-revert='true']
    .hover:is(:hover, :focus-visible)::after {
    transform-origin: 50% 100%;
  }

  ::view-transition-old(root) {
    animation: none;
  }
  ::view-transition-new(root) {
    animation-name: bloom;
    animation-duration: 1.25s;
  }
  @keyframes bloom {
    0% {
      clip-path: circle(0 at 0 0);
    }
    100% {
      clip-path: circle(150vmax at 0 0);
    }
  }
}

@layer base {
  :root {
    --font-size-min: 16;
    --font-size-max: 20;
    --font-ratio-min: 1.2;
    --font-ratio-max: 1.33;
    --font-width-min: 375;
    --font-width-max: 1500;
  }

  html {
    color-scheme: light dark;
  }

  [data-theme='light'] {
    color-scheme: light only;
  }

  [data-theme='dark'] {
    color-scheme: dark only;
  }

  :where(.fluid) {
    --fluid-min: calc(
      var(--font-size-min) * pow(var(--font-ratio-min), var(--font-level, 0))
    );
    --fluid-max: calc(
      var(--font-size-max) * pow(var(--font-ratio-max), var(--font-level, 0))
    );
    --fluid-preferred: calc(
      (var(--fluid-max) - var(--fluid-min)) /
        (var(--font-width-max) - var(--font-width-min))
    );
    --fluid-type: clamp(
      (var(--fluid-min) / 16) * 1rem,
      ((var(--fluid-min) / 16) * 1rem) -
        (((var(--fluid-preferred) * var(--font-width-min)) / 16) * 1rem) +
        (var(--fluid-preferred) * var(--variable-unit, 100vi)),
      (var(--fluid-max) / 16) * 1rem
    );
    font-size: var(--fluid-type);
  }

  *,
  *:after,
  *:before {
    box-sizing: border-box;
  }

  body {
    background: light-dark(#fff, #000);
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 100vh;
    font-family: 'SF Pro Text', 'SF Pro Icons', 'AOS Icons', 'Helvetica Neue',
      Helvetica, Arial, sans-serif, system-ui;
    position: relative;
    overflow: hidden;
    padding: 20px;
  }

  body::before {
    --size: 45px;
    --line: color-mix(in hsl, canvasText, transparent 80%);
    content: '';
    height: 100vh;
    width: 100vw;
    position: fixed;
    background: linear-gradient(
          90deg,
          var(--line) 1px,
          transparent 1px var(--size)
        )
        calc(var(--size) * 0.36) 50% / var(--size) var(--size),
      linear-gradient(var(--line) 1px, transparent 1px var(--size)) 0%
        calc(var(--size) * 0.32) / var(--size) var(--size);
    mask: linear-gradient(-20deg, transparent 50%, white);
    top: 0;
    transform-style: flat;
    pointer-events: none;
    z-index: -1;
  }

  .bear-link {
    color: canvasText;
    position: fixed;
    top: 1rem;
    right: 1rem;
    width: 48px;
    aspect-ratio: 1;
    display: grid;
    place-items: center;
    opacity: 0.8;
    z-index: 1001;
  }

  .actions {
    position: fixed;
    bottom: 40px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    flex-direction: row;
    gap: 1.5rem;
    z-index: 1000;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(20px);
    padding: 20px 30px;
    border-radius: 50px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
  }

  .radio-input {
    display: flex;
    height: 210px;
    align-items: center;
  }

  .glass {
    z-index: 2;
    height: 110%;
    width: 95px;
    margin-right: 25px;
    padding: 8px;
    background-color: rgba(190, 189, 189, 0.5);
    border-radius: 35px;
    box-shadow: rgba(50, 50, 93, 0.2) 0px 25px 50px -10px,
      rgba(0, 0, 0, 0.25) 0px 10px 30px -15px,
      rgba(10, 37, 64, 0.26) 0px -2px 6px 0px inset;
    backdrop-filter: blur(8px);
  }

  .glass-inner {
    width: 100%;
    height: 100%;
    border-color: rgba(245, 245, 245, 0.45);
    border-width: 9px;
    border-style: solid;
    border-radius: 30px;
  }

  .selector {
    display: flex;
    flex-direction: column;
  }

  .choice {
    margin: 10px 0 10px 0;
    display: flex;
    align-items: center;
  }

  .choice > div {
    position: relative;
    width: 41px;
    height: 41px;
    margin-right: 15px;
    z-index: 0;
  }

  .choice-circle {
    appearance: none;
    height: 100%;
    width: 100%;
    border-radius: 100%;
    border-width: 9px;
    border-style: solid;
    border-color: rgba(245, 245, 245, 0.45);
    cursor: pointer;
    box-shadow: 0px 0px 20px -13px gray, 0px 0px 20px -14px gray inset;
  }

  .ball {
    z-index: 1;
    position: absolute;
    inset: 0px;
    transform: translateX(-95px);
    box-shadow: rgba(0, 0, 0, 0.17) 0px -10px 10px 0px inset,
      rgba(0, 0, 0, 0.15) 0px -15px 15px 0px inset,
      rgba(0, 0, 0, 0.1) 0px -40px 20px 0px inset, rgba(0, 0, 0, 0.06) 0px 2px 1px,
      rgba(0, 0, 0, 0.09) 0px 4px 2px, rgba(0, 0, 0, 0.09) 0px 8px 4px,
      rgba(0, 0, 0, 0.09) 0px 16px 8px, rgba(0, 0, 0, 0.09) 0px 32px 16px,
      0px -1px 15px -8px rgba(0, 0, 0, 0.09);
    border-radius: 100%;
    transition: transform 800ms cubic-bezier(1, -0.4, 0, 1.4);
    background-color: rgb(232, 232, 232, 1);
  }

  .choice-circle:checked + .ball {
    transform: translateX(0px);
  }

  .choice-name {
    color: rgb(177, 176, 176);
    font-size: 24px;
    font-weight: 900;
    font-family: monospace;
    cursor: pointer;
  }

  .arrow {
    display: none;
  }

  .about-title {
    font-size: 4.5rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    position: absolute;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    pointer-events: none;
    white-space: nowrap;
    font-family: "Arial Black", "Arial Bold", Arial, sans-serif;
    background: linear-gradient(
        to bottom,
        rgb(8 42 123 / 35%) 30%,
        rgb(255 255 255 / 0%) 76%
    );
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    z-index: 100;
  }

  .carousel-container {
    width: 100%;
    max-width: 900px;
    height: 350px;
    position: relative;
    perspective: 1000px;
    margin-top: 160px;
    margin-bottom: 120px;
    z-index: 10;
  }

  .card {
    position: absolute;
    width: 220px;
    height: 300px;
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
    border: 3px solid transparent;
    background: linear-gradient(white, white) padding-box,
                linear-gradient(135deg, #667eea, #764ba2) border-box;
  }

  .card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  }

  .card.center {
    z-index: 10;
    transform: scale(1.15) translateZ(0);
    box-shadow: 0 35px 70px rgba(0, 0, 0, 0.3);
  }

  .card.center img {
    filter: none;
  }

  .card.left-2 {
    z-index: 1;
    transform: translateX(-280px) scale(0.75) translateZ(-300px);
    opacity: 0.6;
  }

  .card.left-2 img {
    filter: grayscale(100%);
  }

  .card.left-1 {
    z-index: 5;
    transform: translateX(-140px) scale(0.85) translateZ(-100px);
    opacity: 0.8;
  }

  .card.left-1 img {
    filter: grayscale(80%);
  }

  .card.right-1 {
    z-index: 5;
    transform: translateX(140px) scale(0.85) translateZ(-100px);
    opacity: 0.8;
  }

  .card.right-1 img {
    filter: grayscale(80%);
  }

  .card.right-2 {
    z-index: 1;
    transform: translateX(280px) scale(0.75) translateZ(-300px);
    opacity: 0.6;
  }

  .card.right-2 img {
    filter: grayscale(100%);
  }

  .card.hidden {
    opacity: 0;
    pointer-events: none;
  }

  .member-info {
    text-align: center;
    margin-top: 30px;
    margin-bottom: 60px;
    transition: all 0.5s ease-out;
    z-index: 10;
  }

  .member-name {
    color: rgb(8, 42, 123);
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }

  .member-name::before,
  .member-name::after {
    content: "";
    position: absolute;
    top: 100%;
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 2px;
  }

  .member-name::before {
    left: -100px;
  }

  .member-name::after {
    right: -100px;
  }

  .member-role {
    color: #848696;
    font-size: 1.3rem;
    font-weight: 500;
    opacity: 0.9;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 10px 0;
    margin-top: -15px;
    position: relative;
    font-style: italic;
  }

  .dots {
    display: flex;
    justify-content: center;
    gap: 12px;
    margin-top: 30px;
    margin-bottom: 80px;
    z-index: 10;
  }

  .dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    background: rgba(102, 126, 234, 0.3);
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .dot.active {
    background: linear-gradient(135deg, #667eea, #764ba2);
    transform: scale(1.3);
    border-color: rgba(255, 255, 255, 0.5);
    box-shadow: 0 0 20px rgba(102, 126, 234, 0.5);
  }

  .nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    font-size: 1.8rem;
    border: none;
    outline: none;
    padding-bottom: 4px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
  }

  .nav-arrow:hover {
    background: linear-gradient(135deg, #5a6fd8, #6a4190);
    transform: translateY(-50%) scale(1.1);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
  }

  .nav-arrow.left {
    left: 30px;
    padding-right: 3px;
  }

  .nav-arrow.right {
    right: 30px;
    padding-left: 3px;
  }

  /* Utilities */
  .sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border-width: 0;
  }

  @media (max-width: 768px) {
    .about-title {
      font-size: 3.5rem;
      top: 60px;
    }

    .carousel-container {
      margin-top: 120px;
      max-width: 600px;
      height: 250px;
    }

    .card {
      width: 150px;
      height: 200px;
    }

    .card.left-2 {
      transform: translateX(-180px) scale(0.8) translateZ(-300px);
    }

    .card.left-1 {
      transform: translateX(-90px) scale(0.9) translateZ(-100px);
    }

    .card.right-1 {
      transform: translateX(90px) scale(0.9) translateZ(-100px);
    }

    .card.right-2 {
      transform: translateX(180px) scale(0.8) translateZ(-300px);
    }

    .member-name {
      font-size: 1.5rem;
    }

    .member-role {
      font-size: 1rem;
    }

    .member-name::before,
    .member-name::after {
      width: 50px;
    }

    .member-name::before {
      left: -70px;
    }

    .member-name::after {
      right: -70px;
    }

    .actions {
      bottom: 25px;
      gap: 1rem;
      padding: 10px 20px;
    }

    .radio-input {
      height: 180px;
    }

    .glass {
      width: 80px;
      margin-right: 15px;
    }

    .choice-name {
      font-size: 20px;
    }

    .nav-arrow {
      width: 45px;
      height: 45px;
      font-size: 1.5rem;
    }

    .nav-arrow.left {
      left: 15px;
    }

    .nav-arrow.right {
      right: 15px;
    }
  }
}

/* Team Carousel Styles */
.about-title {
    font-size: 5rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: -0.02em;
    position: absolute;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    pointer-events: none;
    white-space: nowrap;
    font-family: "Arial Black", "Arial Bold", Arial, sans-serif;
    background: linear-gradient(
        to bottom,
        rgb(8 42 123 / 35%) 30%,
        rgb(255 255 255 / 0%) 76%
    );
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    z-index: 100;
}

.carousel-container {
    width: 100%;
    max-width: 800px;
    height: 300px;
    position: relative;
    perspective: 1000px;
    margin-top: 150px;
    margin-bottom: 60px;
}

.carousel-track {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    transform-style: preserve-3d;
    transition: transform 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.card {
    position: absolute;
    width: 200px;
    height: 250px;
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    cursor: pointer;
}

.card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: all 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.card.center {
    z-index: 10;
    transform: scale(1.1) translateZ(0);
}

.card.center img {
    filter: none;
}

.card.left-2 {
    z-index: 1;
    transform: translateX(-250px) scale(0.8) translateZ(-300px);
    opacity: 0.7;
}

.card.left-2 img {
    filter: grayscale(100%);
}

.card.left-1 {
    z-index: 5;
    transform: translateX(-120px) scale(0.9) translateZ(-100px);
    opacity: 0.9;
}

.card.left-1 img {
    filter: grayscale(100%);
}

.card.right-1 {
    z-index: 5;
    transform: translateX(120px) scale(0.9) translateZ(-100px);
    opacity: 0.9;
}

.card.right-1 img {
    filter: grayscale(100%);
}

.card.right-2 {
    z-index: 1;
    transform: translateX(250px) scale(0.8) translateZ(-300px);
    opacity: 0.7;
}

.card.right-2 img {
    filter: grayscale(100%);
}

.card.hidden {
    opacity: 0;
    pointer-events: none;
}

.member-info {
    text-align: center;
    margin-top: 20px;
    margin-bottom: 40px;
    transition: all 0.5s ease-out;
}

.member-name {
    color: rgb(8, 42, 123);
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 10px;
    position: relative;
    display: inline-block;
}

.member-name::before,
.member-name::after {
    content: "";
    position: absolute;
    top: 100%;
    width: 100px;
    height: 2px;
    background: rgb(8, 42, 123);
}

.member-name::before {
    left: -120px;
}

.member-name::after {
    right: -120px;
}

.member-role {
    color: #848696;
    font-size: 1.2rem;
    font-weight: 500;
    opacity: 0.8;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    padding: 10px 0;
    margin-top: -15px;
    position: relative;
}

.dots {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 20px;
    margin-bottom: 60px;
}

.dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(8, 42, 123, 0.2);
    cursor: pointer;
    transition: all 0.3s ease;
}

.dot.active {
    background: rgb(8, 42, 123);
    transform: scale(1.2);
}

.nav-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(8, 42, 123, 0.6);
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 20;
    transition: all 0.3s ease;
    font-size: 1.5rem;
    border: none;
    outline: none;
    padding-bottom: 4px;
}

.nav-arrow:hover {
    background: rgba(0, 0, 0, 0.8);
    transform: translateY(-50%) scale(1.1);
}

.nav-arrow.left {
    left: 20px;
    padding-right: 3px;
}

.nav-arrow.right {
    right: 20px;
    padding-left: 3px;
}

@media (max-width: 768px) {
    .about-title {
        font-size: 3rem;
        top: 100px;
    }

    .carousel-container {
        margin-top: 160px;
        max-width: 700px;
        height: 280px;
    }

    .card {
        width: 180px;
        height: 240px;
    }

    .card.left-2 {
        transform: translateX(-200px) scale(0.75) translateZ(-300px);
    }

    .card.left-1 {
        transform: translateX(-100px) scale(0.85) translateZ(-100px);
    }

    .card.right-1 {
        transform: translateX(100px) scale(0.85) translateZ(-100px);
    }

    .card.right-2 {
        transform: translateX(200px) scale(0.75) translateZ(-300px);
    }

    .member-name {
        font-size: 1.8rem;
    }

    .member-role {
        font-size: 1.1rem;
    }

    .member-name::before,
    .member-name::after {
        width: 50px;
    }

    .member-name::before {
        left: -70px;
    }

    .member-name::after {
        right: -70px;
    }

    .actions {
        top: 20px;
        left: 20px;
        gap: 1rem;
        padding: 10px 10px;
    }

    .nav-arrow {
        width: 45px;
        height: 45px;
        font-size: 1.5rem;
    }

    .nav-arrow.left {
        left: 15px;
    }

    .nav-arrow.right {
        right: 15px;
    }
}
.Btn {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  width: 45px;
  height: 45px;
  border: none;
  border-radius: 50%;
  cursor: pointer;
  position: relative;
  overflow: hidden;
  transition-duration: .3s;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.199);
  background-color: rgba(73, 72, 72, 0.3);
}

/* plus sign */
.sign {
  width: 100%;
  transition-duration: .3s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.sign svg {
  width: 17px;
}

.sign svg path {
  fill: white;
}
/* text */
.text {
  position: absolute;
  right: 0%;
  width: 0%;
  opacity: 0;
  color: white;
  font-size: 1.2em;
  font-weight: 600;
  transition-duration: .3s;
}
/* hover effect on button width */
.Btn:hover {
  width: 125px;
  border-radius: 40px;
  transition-duration: .3s;
}

.Btn:hover .sign {
  width: 30%;
  transition-duration: .3s;
  padding-left: 20px;
}
/* hover effect button's text */
.Btn:hover .text {
  opacity: 1;
  width: 70%;
  transition-duration: .3s;
  padding-right: 10px;
}
/* button click effect*/
.Btn:active {
  transform: translate(2px ,2px);
}
    </style>
</head>
<body>
  
    <div class="actions">
        <div class="radio-input">
            <div class="glass">
                <div class="glass-inner"></div>
            </div>
            <div class="selector">
                <div class="choice">
                    <div>
                        <input class="choice-circle" value="liste" name="page-selector" id="liste" type="radio" checked="true">
                        <div class="ball"></div>
                    </div>
                    <label for="liste" class="choice-name" onclick="location.href='./listedeslivre.php'">Liste</label>
                </div>
                <div class="choice">
                    <div>
                        <input class="choice-circle" value="ajouter" name="page-selector" id="ajouter" type="radio">
                        <div class="ball"></div>
                    </div>
                    <label for="ajouter" class="choice-name" onclick="location.href='./formulaire_gestionnaire.php'">Ajouter</label>
                </div>
                <div class="choice">
                    <div>
                        <input class="choice-circle" value="apercu" name="page-selector" id="apercu" type="radio">
                        <div class="ball"></div>
                    </div>
                    <label for="apercu" class="choice-name" onclick="location.href='./aperçu.php'">Aperçu</label>
                </div>
                <div class="choice">
                    <div>
                        <input class="choice-circle" value="apropos" name="page-selector" id="apropos" type="radio">
                        <div class="ball"></div>
                    </div>
                    <label for="apropos" class="choice-name" onclick="location.href='./apropos.php'">Apropos</label>
                </div>
            </div>
        </div>
        <a href="logout.php" class="logout-button">
        <button class="Btn">
  
           <div class="sign"><svg viewBox="0 0 512 512">
            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z">
            </path></svg></div>
           <div class="text">Logout</div>
           </button>
        </a>
    </div>
    <a
      aria-label="🇲🇬 Bëlhardo Ravelonantenaina 🇲🇬 󱢏 "
      class="bear-link"
      href="https://www.facebook.com/ravelonantenainabelhardo"
      target="_blank"
      rel="noreferrer noopener"
    >
      <svg
        class="w-9"
        viewBox="0 0 969 955"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
      >
        <circle
          cx="161.191"
          cy="320.191"
          r="133.191"
          stroke="currentColor"
          stroke-width="20"
        ></circle>
        <circle
          cx="806.809"
          cy="320.191"
          r="133.191"
          stroke="currentColor"
          stroke-width="20"
        ></circle>
        <circle
          cx="695.019"
          cy="587.733"
          r="31.4016"
          fill="currentColor"
        ></circle>
        <circle
          cx="272.981"
          cy="587.733"
          r="31.4016"
          fill="currentColor"
        ></circle>
        <path
          d="M564.388 712.083C564.388 743.994 526.035 779.911 483.372 779.911C440.709 779.911 402.356 743.994 402.356 712.083C402.356 680.173 440.709 664.353 483.372 664.353C526.035 664.353 564.388 680.173 564.388 712.083Z"
          fill="currentColor"
        ></path>
        <rect
          x="310.42"
          y="448.31"
          width="343.468"
          height="51.4986"
          fill="#FF1E1E"
        ></rect>
        <path
          fill-rule="evenodd"
          clip-rule="evenodd"
          d="M745.643 288.24C815.368 344.185 854.539 432.623 854.539 511.741H614.938V454.652C614.938 433.113 597.477 415.652 575.938 415.652H388.37C366.831 415.652 349.37 433.113 349.37 454.652V511.741L110.949 511.741C110.949 432.623 150.12 344.185 219.845 288.24C289.57 232.295 384.138 200.865 482.744 200.865C581.35 200.865 675.918 232.295 745.643 288.24Z"
          fill="currentColor"
        ></path>
      </svg>
    </a>

    <!-- Team Carousel Section -->
    <h1 class="about-title">LIVRES CÉLÈBRES</h1>

    <div class="carousel-container">
        <button class="nav-arrow left">‹</button>
        <div class="carousel-track">
            <div class="card" data-index="0">
                <img src="https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&auto=format&fit=crop&q=80" alt="Le Petit Prince">
            </div>
            <div class="card" data-index="1">
                <img src="https://images.unsplash.com/photo-1541963463532-d68292c34b19?w=800&auto=format&fit=crop&q=80" alt="1984">
            </div>
            <div class="card" data-index="2">
                <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&auto=format&fit=crop&q=80" alt="Don Quichotte">
            </div>
            <div class="card" data-index="3">
                <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&auto=format&fit=crop&q=80" alt="L'Étranger">
            </div>
            <div class="card" data-index="4">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80" alt="Madame Bovary">
            </div>
            <div class="card" data-index="5">
                <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=800&auto=format&fit=crop&q=80" alt="Les Misérables">
            </div>
        </div>
        <button class="nav-arrow right">›</button>
    </div>

    <div class="member-info">
        <h2 class="member-name">Antoine de Saint-Exupéry</h2>
        <p class="member-role">Le Petit Prince</p>
    </div>

    <div class="dots">
        <div class="dot active" data-index="0"></div>
        <div class="dot" data-index="1"></div>
        <div class="dot" data-index="2"></div>
        <div class="dot" data-index="3"></div>
        <div class="dot" data-index="4"></div>
        <div class="dot" data-index="5"></div>
    </div>
</body>
<script>
    import { Pane } from 'https://cdn.skypack.dev/tweakpane@4.0.4'

const CONFIG = {
  revert: true,
  vertical: true,
  intent: true,
  theme: 'system',
}

const CTRL = new Pane({
  title: 'config',
})

CTRL.addBinding(CONFIG, 'vertical')
CTRL.addBinding(CONFIG, 'revert')
CTRL.addBinding(CONFIG, 'intent', {
  label: 'delay',
})
CTRL.addBinding(CONFIG, 'theme', {
  label: 'theme',
  options: {
    system: 'system',
    light: 'light',
    dark: 'dark',
  },
})

const update = () => {
  document.documentElement.dataset.vertical = CONFIG.vertical
  document.documentElement.dataset.revert = CONFIG.revert
  document.documentElement.dataset.intent = CONFIG.intent
  document.documentElement.dataset.theme = CONFIG.theme
}

const sync = (event) => {
  if (
    !document.startViewTransition ||
    event.target.controller.view.labelElement.innerText !== 'theme'
  )
    return update()
  document.startViewTransition(() => update())
}

CTRL.on('change', sync)

update()

</script>

<!-- Team Carousel JavaScript -->
<script>
const teamMembers = [
    { name: "Antoine de Saint-Exupéry", role: "Le Petit Prince" },
    { name: "George Orwell", role: "1984" },
    { name: "Miguel de Cervantes", role: "Don Quichotte" },
    { name: "Albert Camus", role: "L'Étranger" },
    { name: "Gustave Flaubert", role: "Madame Bovary" },
    { name: "Victor Hugo", role: "Les Misérables" }
];

const cards = document.querySelectorAll(".card");
const dots = document.querySelectorAll(".dot");
const memberName = document.querySelector(".member-name");
const memberRole = document.querySelector(".member-role");
const leftArrow = document.querySelector(".nav-arrow.left");
const rightArrow = document.querySelector(".nav-arrow.right");
let currentIndex = 0;
let isAnimating = false;

function updateCarousel(newIndex) {
    if (isAnimating) return;
    isAnimating = true;

    currentIndex = (newIndex + cards.length) % cards.length;

    cards.forEach((card, i) => {
        const offset = (i - currentIndex + cards.length) % cards.length;

        card.classList.remove(
            "center",
            "left-1",
            "left-2",
            "right-1",
            "right-2",
            "hidden"
        );

        if (offset === 0) {
            card.classList.add("center");
        } else if (offset === 1) {
            card.classList.add("right-1");
        } else if (offset === 2) {
            card.classList.add("right-2");
        } else if (offset === cards.length - 1) {
            card.classList.add("left-1");
        } else if (offset === cards.length - 2) {
            card.classList.add("left-2");
        } else {
            card.classList.add("hidden");
        }
    });

    dots.forEach((dot, i) => {
        dot.classList.toggle("active", i === currentIndex);
    });

    memberName.style.opacity = "0";
    memberRole.style.opacity = "0";

    setTimeout(() => {
        memberName.textContent = teamMembers[currentIndex].name;
        memberRole.textContent = teamMembers[currentIndex].role;
        memberName.style.opacity = "1";
        memberRole.style.opacity = "1";
    }, 300);

    setTimeout(() => {
        isAnimating = false;
    }, 800);
}

leftArrow.addEventListener("click", () => {
    updateCarousel(currentIndex - 1);
});

rightArrow.addEventListener("click", () => {
    updateCarousel(currentIndex + 1);
});

dots.forEach((dot, i) => {
    dot.addEventListener("click", () => {
        updateCarousel(i);
    });
});

cards.forEach((card, i) => {
    card.addEventListener("click", () => {
        updateCarousel(i);
    });
});

document.addEventListener("keydown", (e) => {
    if (e.key === "ArrowLeft") {
        updateCarousel(currentIndex - 1);
    } else if (e.key === "ArrowRight") {
        updateCarousel(currentIndex + 1);
    }
});

let touchStartX = 0;
let touchEndX = 0;

document.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
});

document.addEventListener("touchend", (e) => {
    touchEndX = e.changedTouches[0].screenX;
    handleSwipe();
});

function handleSwipe() {
    const swipeThreshold = 50;
    const diff = touchStartX - touchEndX;

    if (Math.abs(diff) > swipeThreshold) {
        if (diff > 0) {
            updateCarousel(currentIndex + 1);
        } else {
            updateCarousel(currentIndex - 1);
        }
    }
}

updateCarousel(0);
</script>
</html>