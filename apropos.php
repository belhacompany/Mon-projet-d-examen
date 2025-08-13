<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À Propos - Projet Universitaire</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Observer.min.js"></script>
</head>
<style>
@import url("https://fonts.cdnfonts.com/css/thegoodmonolith");

*,
*::after,
*::before {
  box-sizing: border-box;
}

:root {
  --color-text: #fff;
  --color-bg: #000;
  --thumb-width: 120px;
  --line-spacing: 10px;
  --line-base-height: 15px;
  --line-max-height: 50px;
}

body {
  margin: 0;
  color: var(--color-text);
  background-color: var(--color-bg);
  font-family: "TheGoodMonolith", sans-serif;
  overflow: hidden;
  height: 100vh;
}

.slides {
  width: 100%;
  height: 100vh;
  overflow: hidden;
  display: grid;
  grid-template-rows: 100%;
  grid-template-columns: 100%;
  place-items: center;
}

.slide {
  width: 100%;
  height: 100%;
  grid-area: 1 / 1 / -1 / -1;
  pointer-events: none;
  opacity: 0;
  overflow: hidden;
  position: relative;
  display: grid;
  place-items: center;
  will-change: transform, opacity;
}

.slide--current {
  pointer-events: auto;
  opacity: 1;
}

.slide__img {
  width: 100%;
  height: 100%;
  background-size: cover;
  background-position: left center;
  background-repeat: no-repeat;
  will-change: transform, opacity, filter;
}

/* Première slide avec image réduite et plus sombre */
.slide:first-child .slide__img {
  background-size: 80%;
  background-position: center;
  filter: brightness(0.6) contrast(1.2);
}

/* Overlay sombre pour la première slide */
.slide:first-child::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1;
  pointer-events: none;
}

.scroll-hint {
  position: fixed;
  top: 2rem;
  right: 2rem;
  color: #fff;
  z-index: 100;
  font-size: 1rem;
}

/* Bottom UI container */
.bottom-ui-container {
  position: fixed;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 720px;
  max-width: 100%;
  z-index: 100;
  padding-bottom: 2rem;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.slide-section {
  color: #fff;
  font-size: 1.8rem;
  font-weight: bold;
  width: 100%;
  text-align: center;
  opacity: 0.9;
  letter-spacing: 1px;
  margin-bottom: 36px;
}

.slide-counter {
  display: flex;
  align-items: center;
  width: 100%;
  justify-content: space-between;
  color: #fff;
  font-size: 0.825rem;
  margin-bottom: 24px;
}

.counter-display {
  display: flex;
  align-items: center;
  gap: 10px;
}

.counter-nav {
  width: 20px;
  cursor: pointer;
  opacity: 0.7;
  transition: opacity 0.3s;
}

.counter-nav:hover {
  opacity: 1;
}

.counter-divider {
  opacity: 0.6;
  font-size: 0.8rem;
}

.slide-title-container {
  width: 100%;
  text-align: center;
  height: 30px;
  overflow: hidden;
  margin-bottom: 16px;
  position: relative;
}

.slide-title {
  position: absolute;
  width: 100%;
  color: #fff;
  font-size: 1.2rem;
  opacity: 0.8;
  transition: transform 0.5s ease, opacity 0.5s ease;
  left: 0;
}

.slide-title.exit-up {
  transform: translateY(-30px);
  opacity: 0;
}

.slide-title.enter-up {
  transform: translateY(30px);
  opacity: 0;
}

/* Updated drag indicator styles */
.drag-indicator {
  width: 100%;
  height: 50px;
  pointer-events: none;
  margin-bottom: 8px;
  position: relative;
}

.lines-container {
  display: flex;
  height: 100%;
  width: 100%;
  position: relative;
  align-items: flex-end;
  justify-content: space-between;
}

.drag-line {
  width: 2px;
  background-color: rgba(255, 255, 255, 0.3);
  height: var(--line-base-height);
  transform-origin: bottom center;
  transition: height 0.6s cubic-bezier(0.25, 0.1, 0.25, 1),
    background-color 0.6s cubic-bezier(0.25, 0.1, 0.25, 1);
}

.thumbs-container {
  width: 100%;
  background: rgba(0, 0, 0, 0.5);
  overflow: hidden;
}

.slide-thumbs {
  display: flex;
  position: relative;
  background: transparent;
  padding: 0;
  z-index: 11;
  gap: 0;
}

.frost-bg {
  display: none;
}

.slide-thumb {
  width: var(--thumb-width);
  height: 80px;
  background-size: cover;
  background-position: left center;
  cursor: pointer;
  opacity: 0.7;
  transition: opacity 0.3s;
  border: none;
  outline: none;
  box-shadow: none;
  margin: 0;
  position: relative;
  z-index: 12;
}

.slide-thumb:hover {
  opacity: 0.8;
}

.slide-thumb.active {
  opacity: 1;
  transform: none;
  border: none;
  outline: none;
  box-shadow: none;
}

/* Back button styling */
.btn-retour {
  position: fixed;
  top: 30px;
  left: 30px;
  z-index: 200;
  background: #fff;
  color: #212121;
  padding: 10px 22px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: bold;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  transition: background 0.2s;
  font-family: "TheGoodMonolith", sans-serif;
}

/* Contenu de la première slide au-dessus de l'overlay */
.slide:first-child .slide-content {
  z-index: 2;
  position: relative;
}

.btn-retour:hover {
  background: #f0f0f0;
}

.btn-retour svg {
  width: 24px;
  height: 24px;
  vertical-align: middle;
  margin-right: 8px;
}
</style>
<body>
    <a href="affichageacceuil.php" class="btn-retour">
        <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12.71 16.29 8.41 12l4.3-4.29-1.42-1.42L5.59 12l5.7 5.71z"/>
            <path d="M16.29 6.29 10.59 12l5.7 5.71 1.42-1.42-4.3-4.29 4.3-4.29z"/>
        </svg>
        Retour
    </a>

    <div class="scroll-hint">By BelhaDsign</div>

    <!-- Bottom UI container that holds all bottom elements -->
    <div class="bottom-ui-container">
        <div class="slide-section">PROJET UNIVERSITAIRE</div>
        <div class="slide-counter">
            <div class="counter-nav prev-slide">⟪</div>
            <div class="counter-display">
                <span class="current-slide">01</span>
                <span class="counter-divider">//</span>
                <span class="total-slides">05</span>
            </div>
            <div class="counter-nav next-slide">⟫</div>
        </div>
        <div class="slide-title-container">
            <div class="slide-title">Projet Universitaire</div>
        </div>
        <div class="drag-indicator"></div>
        <div class="thumbs-container">
            <div class="frost-bg"></div>
            <div class="slide-thumbs"></div>
        </div>
    </div>

    <div class="slides">
        <div class="slide">
            <div class="slide__img" style="background-image: url('./sary3.jpg')"></div>
        </div>
        <div class="slide">
            <div class="slide__img" style="background-image: url('https://images.unsplash.com/photo-1481627834876-b7833e8f5570?auto=format&fit=crop&w=1200&q=80')"></div>
        </div>
        <div class="slide">
            <div class="slide__img" style="background-image: url('https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1200&q=80')"></div>
        </div>
        <div class="slide">
            <div class="slide__img" style="background-image: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1200&q=80')"></div>
        </div>
        <div class="slide">
            <div class="slide__img" style="background-image: url('https://images.unsplash.com/photo-1503676382389-4809596d5290?auto=format&fit=crop&w=1200&q=80')"></div>
        </div>
    </div>

<script>
// Direction constants
const NEXT = 1;
const PREV = -1;

// Slide titles array (global) - adapted to your content
const slideTitles = [
  "Projet Universitaire",
  "Partage ta passion de la lecture",
  "Persévérance",
  "Ose apprendre",
  "Travail d'équipe"
];

// Global variable to track currently hovered thumbnail
let currentHoveredThumb = null;

// Global variable to track mouse position over thumbnails
let mouseOverThumbnails = false;
let lastHoveredThumbIndex = null;

// Global animation state management
let isAnimating = false;
let pendingNavigation = null;

// Function to visually update navigation elements based on animation state
function updateNavigationUI(disabled) {
  // Update navigation arrows
  const navButtons = document.querySelectorAll(".counter-nav");
  navButtons.forEach((btn) => {
    btn.style.opacity = disabled ? "0.3" : "";
    btn.style.pointerEvents = disabled ? "none" : "";
  });

  // Update thumbnails
  const thumbs = document.querySelectorAll(".slide-thumb");
  thumbs.forEach((thumb) => {
    thumb.style.pointerEvents = disabled ? "none" : "";
  });
}

// Global functions for slide management
function updateSlideCounter(index) {
  const currentSlideEl = document.querySelector(".current-slide");
  if (currentSlideEl) {
    currentSlideEl.textContent = String(index + 1).padStart(2, "0");
  }
}

function updateSlideTitle(index) {
  const titleContainer = document.querySelector(".slide-title-container");
  const currentTitle = document.querySelector(".slide-title");
  if (!titleContainer || !currentTitle) return;

  // Create a new title element
  const newTitle = document.createElement("div");
  newTitle.className = "slide-title enter-up";
  newTitle.textContent = slideTitles[index];

  // Add it to the container
  titleContainer.appendChild(newTitle);

  // Add exit animation class to old title
  currentTitle.classList.add("exit-up");

  // Force reflow
  void newTitle.offsetWidth;

  // Start entrance animation
  setTimeout(() => {
    newTitle.classList.remove("enter-up");
  }, 10);

  // Remove old title after animation completes
  setTimeout(() => {
    currentTitle.remove();
  }, 500);
}

// Updated updateDragLines function for continuous lines
function updateDragLines(activeIndex, forceUpdate = false) {
  const lines = document.querySelectorAll(".drag-line");
  if (!lines.length) return;

  // Reset all lines immediately
  lines.forEach((line) => {
    line.style.height = "var(--line-base-height)";
    line.style.backgroundColor = "rgba(255, 255, 255, 0.3)";
  });

  // If no active index is provided, return
  if (activeIndex === null) {
    return;
  }

  const slideCount = document.querySelectorAll(".slide").length;
  const lineCount = lines.length;

  // Calculate the center position of the active thumbnail
  const thumbWidth = 720 / slideCount; // Total width divided by number of slides
  const centerPosition = (activeIndex + 0.5) * thumbWidth;

  // Calculate the width of one line section
  const lineWidth = 720 / lineCount;

  // Apply the wave pattern to all lines based on distance from center
  for (let i = 0; i < lineCount; i++) {
    // Calculate the center position of this line
    const linePosition = (i + 0.5) * lineWidth;

    // Calculate distance from the center of the active thumbnail
    const distFromCenter = Math.abs(linePosition - centerPosition);

    // Calculate the maximum distance for influence (half a thumbnail width plus a bit)
    const maxDistance = thumbWidth * 0.7;

    // Only affect lines within the influence range
    if (distFromCenter <= maxDistance) {
      // Calculate normalized distance (0 at center, 1 at edge of influence)
      const normalizedDist = distFromCenter / maxDistance;

      // Create a cosine wave pattern (1 at center, 0 at edge)
      const waveHeight = Math.cos((normalizedDist * Math.PI) / 2);

      // Scale the height based on the wave pattern (taller in center)
      const height =
        parseInt(
          getComputedStyle(document.documentElement).getPropertyValue(
            "--line-base-height"
          )
        ) +
        waveHeight * 35;

      // Calculate opacity based on distance (more opaque at center)
      const opacity = 0.3 + waveHeight * 0.4;

      // Stagger the animations slightly based on distance from center
      const delay = normalizedDist * 100;

      // If forceUpdate is true, apply immediately without checking hover state
      if (forceUpdate) {
        lines[i].style.height = `${height}px`;
        lines[i].style.backgroundColor = `rgba(255, 255, 255, ${opacity})`;
      } else {
        setTimeout(() => {
          // Only apply if this is still the current hovered thumbnail
          // or if we're forcing an update
          if (
            currentHoveredThumb === activeIndex ||
            (mouseOverThumbnails && lastHoveredThumbIndex === activeIndex)
          ) {
            lines[i].style.height = `${height}px`;
            lines[i].style.backgroundColor = `rgba(255, 255, 255, ${opacity})`;
          }
        }, delay);
      }
    }
  }
}

class Slideshow {
  DOM = {
    el: null,
    slides: null,
    slidesInner: null
  };
  current = 0;
  slidesTotal = 0;

  constructor(DOM_el) {
    this.DOM.el = DOM_el;
    this.DOM.slides = [...this.DOM.el.querySelectorAll(".slide")];
    this.DOM.slidesInner = this.DOM.slides.map((item) =>
      item.querySelector(".slide__img")
    );
    this.DOM.slides[this.current].classList.add("slide--current");
    this.slidesTotal = this.DOM.slides.length;
  }

  next() {
    this.navigate(NEXT);
  }

  prev() {
    this.navigate(PREV);
  }

  // Method to navigate to a specific slide index
  goTo(index) {
    // If already animating, store this as pending navigation
    if (isAnimating) {
      pendingNavigation = { type: "goto", index };
      return false;
    }

    // Don't navigate if it's the current slide
    if (index === this.current) return false;

    // Set animation state
    isAnimating = true;
    updateNavigationUI(true);

    const previous = this.current;
    this.current = index;

    // Update active thumbnail
    const thumbs = document.querySelectorAll(".slide-thumb");
    thumbs.forEach((thumb, i) => {
      thumb.classList.toggle("active", i === index);
    });

    // Update counter and title
    updateSlideCounter(index);
    updateSlideTitle(index);

    // Show drag lines for active thumbnail
    updateDragLines(index, true);

    // Determine direction for the animation
    const direction = index > previous ? 1 : -1;

    // Get slides and perform animation
    const currentSlide = this.DOM.slides[previous];
    const currentInner = this.DOM.slidesInner[previous];
    const upcomingSlide = this.DOM.slides[index];
    const upcomingInner = this.DOM.slidesInner[index];

    gsap
      .timeline({
        onStart: () => {
          this.DOM.slides[index].classList.add("slide--current");
          gsap.set(upcomingSlide, { zIndex: 99 });
        },
        onComplete: () => {
          this.DOM.slides[previous].classList.remove("slide--current");
          gsap.set(upcomingSlide, { zIndex: 1 });

          // Reset animation state
          isAnimating = false;
          updateNavigationUI(false);

          // Check if there's a pending navigation
          if (pendingNavigation) {
            const { type, index, direction } = pendingNavigation;
            pendingNavigation = null;

            // Execute the pending navigation after a small delay
            setTimeout(() => {
              if (type === "goto") {
                this.goTo(index);
              } else if (type === "navigate") {
                this.navigate(direction);
              }
            }, 50);
          }

          // Re-apply hover effect if mouse is still over thumbnails
          if (mouseOverThumbnails && lastHoveredThumbIndex !== null) {
            currentHoveredThumb = lastHoveredThumbIndex;
            updateDragLines(lastHoveredThumbIndex, true);
          }
        }
      })
      .addLabel("start", 0)
      .fromTo(
        upcomingSlide,
        {
          autoAlpha: 1,
          scale: 0.1,
          yPercent: direction === 1 ? 100 : -100 // Bottom for next, top for prev
        },
        {
          duration: 0.7,
          ease: "expo",
          scale: 0.4,
          yPercent: 0
        },
        "start"
      )
      .fromTo(
        upcomingInner,
        {
          filter: "contrast(100%) saturate(100%)",
          transformOrigin: "100% 50%",
          scaleY: 4
        },
        {
          duration: 0.7,
          ease: "expo",
          scaleY: 1
        },
        "start"
      )
      .fromTo(
        currentInner,
        {
          filter: "contrast(100%) saturate(100%)"
        },
        {
          duration: 0.7,
          ease: "expo",
          filter: "contrast(120%) saturate(140%)"
        },
        "start"
      )
      .addLabel("middle", "start+=0.6")
      .to(
        upcomingSlide,
        {
          duration: 1,
          ease: "power4.inOut",
          scale: 1
        },
        "middle"
      )
      .to(
        currentSlide,
        {
          duration: 1,
          ease: "power4.inOut",
          scale: 0.98,
          autoAlpha: 0
        },
        "middle"
      );
  }

  navigate(direction) {
    // If already animating, store this as pending navigation
    if (isAnimating) {
      pendingNavigation = { type: "navigate", direction };
      return false;
    }

    // Set animation state
    isAnimating = true;
    updateNavigationUI(true);

    const previous = this.current;
    this.current =
      direction === 1
        ? this.current < this.slidesTotal - 1
          ? ++this.current
          : 0
        : this.current > 0
        ? --this.current
        : this.slidesTotal - 1;

    // Update active thumbnail
    const thumbs = document.querySelectorAll(".slide-thumb");
    thumbs.forEach((thumb, index) => {
      if (index === this.current) {
        thumb.classList.add("active");
      } else {
        thumb.classList.remove("active");
      }
    });

    // Update counter and title
    updateSlideCounter(this.current);
    updateSlideTitle(this.current);

    // Highlight active thumbnail in drag line indicator
    updateDragLines(this.current, true);

    // Get slides and perform animation
    const currentSlide = this.DOM.slides[previous];
    const currentInner = this.DOM.slidesInner[previous];
    const upcomingSlide = this.DOM.slides[this.current];
    const upcomingInner = this.DOM.slidesInner[this.current];

    gsap
      .timeline({
        onStart: () => {
          this.DOM.slides[this.current].classList.add("slide--current");
          gsap.set(upcomingSlide, { zIndex: 99 });
        },
        onComplete: () => {
          this.DOM.slides[previous].classList.remove("slide--current");
          gsap.set(upcomingSlide, { zIndex: 1 });

          // Reset animation state
          isAnimating = false;
          updateNavigationUI(false);

          // Check if there's a pending navigation
          if (pendingNavigation) {
            const { type, index, direction } = pendingNavigation;
            pendingNavigation = null;

            // Execute the pending navigation after a small delay
            setTimeout(() => {
              if (type === "goto") {
                this.goTo(index);
              } else if (type === "navigate") {
                this.navigate(direction);
              }
            }, 50);
          }

          // Re-apply hover effect if mouse is still over thumbnails
          if (mouseOverThumbnails && lastHoveredThumbIndex !== null) {
            currentHoveredThumb = lastHoveredThumbIndex;
            updateDragLines(lastHoveredThumbIndex, true);
          }
        }
      })
      .addLabel("start", 0)
      .fromTo(
        upcomingSlide,
        {
          autoAlpha: 1,
          scale: 0.1,
          yPercent: direction === 1 ? 100 : -100 // Bottom for next, top for prev
        },
        {
          duration: 0.7,
          ease: "expo",
          scale: 0.4,
          yPercent: 0
        },
        "start"
      )
      .fromTo(
        upcomingInner,
        {
          filter: "contrast(100%) saturate(100%)",
          transformOrigin: "100% 50%",
          scaleY: 4
        },
        {
          duration: 0.7,
          ease: "expo",
          scaleY: 1
        },
        "start"
      )
      .fromTo(
        currentInner,
        {
          filter: "contrast(100%) saturate(100%)"
        },
        {
          duration: 0.7,
          ease: "expo",
          filter: "contrast(120%) saturate(140%)"
        },
        "start"
      )
      .addLabel("middle", "start+=0.6")
      .to(
        upcomingSlide,
        {
          duration: 1,
          ease: "power4.inOut",
          scale: 1
        },
        "middle"
      )
      .to(
        currentSlide,
        {
          duration: 1,
          ease: "power4.inOut",
          scale: 0.98,
          autoAlpha: 0
        },
        "middle"
      );
  }
}

// Initialize
document.addEventListener("DOMContentLoaded", () => {
  // Create slideshow instance
  const slides = document.querySelector(".slides");
  const slideshow = new Slideshow(slides);

  // Create thumbnails
  const thumbsContainer = document.querySelector(".slide-thumbs");
  const slideImgs = document.querySelectorAll(".slide__img");
  const slideCount = slideImgs.length;

  // Clear thumbs container first (in case it had any previous content)
  if (thumbsContainer) {
    thumbsContainer.innerHTML = "";
    slideImgs.forEach((img, index) => {
      const bgImg = img.style.backgroundImage;
      const thumb = document.createElement("div");
      thumb.className = "slide-thumb";
      thumb.style.backgroundImage = bgImg;
      if (index === 0) {
        thumb.classList.add("active");
      }

      // Animation for clicking on thumbnails - use goTo method
      thumb.addEventListener("click", () => {
        // Store the clicked thumbnail index for later
        lastHoveredThumbIndex = index;

        // Use the new goTo method which handles animation state
        slideshow.goTo(index);
      });

      // Add hover effect to thumbnails with global tracking
      thumb.addEventListener("mouseenter", () => {
        // Update the global variable to track which thumbnail is hovered
        currentHoveredThumb = index;
        lastHoveredThumbIndex = index;
        mouseOverThumbnails = true;

        // Only update lines if not animating
        if (!isAnimating) {
          updateDragLines(index, true);
        }
      });

      thumb.addEventListener("mouseleave", () => {
        // Only reset if we're leaving this specific thumbnail
        // This prevents resetting when moving directly to another thumbnail
        if (currentHoveredThumb === index) {
          currentHoveredThumb = null;
          // Don't reset lastHoveredThumbIndex here
        }
      });

      thumbsContainer.appendChild(thumb);
    });
  }

  // Create continuous drag indicator lines
  const dragIndicator = document.querySelector(".drag-indicator");
  if (dragIndicator) {
    dragIndicator.innerHTML = "";

    // Create a container for the lines to ensure consistent positioning
    const linesContainer = document.createElement("div");
    linesContainer.className = "lines-container";
    dragIndicator.appendChild(linesContainer);

    // Create evenly spaced lines across the entire width
    const totalLines = 60; // Increased number of lines for smoother appearance
    for (let i = 0; i < totalLines; i++) {
      const line = document.createElement("div");
      line.className = "drag-line";
      linesContainer.appendChild(line);
    }
  }

  // Set total slides
  const totalSlidesEl = document.querySelector(".total-slides");
  if (totalSlidesEl) {
    totalSlidesEl.textContent = String(slideCount).padStart(2, "0");
  }

  // Add navigation handlers - use direct methods instead of throttled versions
  const prevButton = document.querySelector(".prev-slide");
  const nextButton = document.querySelector(".next-slide");

  if (prevButton) {
    prevButton.addEventListener("click", () => slideshow.prev());
  }

  if (nextButton) {
    nextButton.addEventListener("click", () => slideshow.next());
  }

  // Initialize counters and lines
  updateSlideCounter(0);
  updateDragLines(0, true); // Initialize the first thumbnail's lines

  // Add global mouse leave handler for the entire thumbnails area
  const thumbsArea = document.querySelector(".thumbs-container");
  if (thumbsArea) {
    thumbsArea.addEventListener("mouseenter", () => {
      mouseOverThumbnails = true;
    });

    thumbsArea.addEventListener("mouseleave", () => {
      // Reset all lines when mouse leaves the entire thumbnails area
      mouseOverThumbnails = false;
      currentHoveredThumb = null;
      updateDragLines(null);
    });
  }

  // Initialize GSAP Observer for scroll/drag with animation state check
  try {
    // First try using it directly
    if (typeof Observer !== "undefined") {
      Observer.create({
        type: "wheel,touch,pointer",
        onDown: () => {
          if (!isAnimating) slideshow.prev();
        },
        onUp: () => {
          if (!isAnimating) slideshow.next();
        },
        wheelSpeed: -1,
        tolerance: 10
      });
    }
    // Then try from GSAP
    else if (typeof gsap.Observer !== "undefined") {
      gsap.Observer.create({
        type: "wheel,touch,pointer",
        onDown: () => {
          if (!isAnimating) slideshow.prev();
        },
        onUp: () => {
          if (!isAnimating) slideshow.next();
        },
        wheelSpeed: -1,
        tolerance: 10
      });
    }
    // Fallback
    else {
      console.warn("GSAP Observer plugin not found, using fallback");

      // Add wheel event listener with animation state check
      document.addEventListener("wheel", (e) => {
        if (isAnimating) return;

        if (e.deltaY > 0) {
          slideshow.next();
        } else {
          slideshow.prev();
        }
      });

      // Add touch events with animation state check
      let touchStartY = 0;

      document.addEventListener("touchstart", (e) => {
        touchStartY = e.touches[0].clientY;
      });

      document.addEventListener("touchend", (e) => {
        if (isAnimating) return;

        const touchEndY = e.changedTouches[0].clientY;
        const diff = touchEndY - touchStartY;

        if (Math.abs(diff) > 50) {
          if (diff > 0) {
            slideshow.prev();
          } else {
            slideshow.next();
          }
        }
      });
    }
  } catch (error) {
    console.error("Error initializing Observer:", error);
  }

  // Keyboard navigation with animation state check
  document.addEventListener("keydown", (e) => {
    if (isAnimating) return;

    if (e.key === "ArrowRight") slideshow.next();
    else if (e.key === "ArrowLeft") slideshow.prev();
  });
});
</script>
</body>
</html>