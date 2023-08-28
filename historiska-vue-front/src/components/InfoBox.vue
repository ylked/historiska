<script lang="ts">
  import {defineComponent} from "vue";

  export default defineComponent({
      props: {
          title: String,
          text: String
      },
      data() {
          return {
              expanded: true,
              animate: false,
              collapsed: {
                  x: 0,
                  y: 0
              },
              showingInfoIcon: true,
          }
      },
      mounted() {
          this.calculateScales();
          this.createEaseAnimations();
          this.addEventListeners();

          this.collapse();
          this.activate();
      },
      methods: {
          activate () {
              this.$refs.box.classList.add('box--active');
              this.animate = true;
          },
          collapse () {
              // Close the pop
              if (this.expanded) {
                  this.expanded = false;

                  const {x, y} = this.collapsed;
                  const invX = 1 / x;
                  const invY = 1 / y;

                  this.$refs.box.style.transform = `scale(${x}, ${y})`;
                  this.$refs.infoBoxContent.style.transform = `scale(${invX}, ${invY})`;

                  if (this.animate) {
                      this.applyAnimation({expand: false});
                  }
              }
          },
          expand () {
              if (!this.expanded) {
                  this.expanded = true;

                  this.$refs.box.style.transform = `scale(1, 1)`;
                  this.$refs.infoBoxContent.style.transform = `scale(1, 1)`;

                  if (this.animate) {
                      this.applyAnimation({expand: true});
                  }
              }
          },
          toggle () {
              if (this.expanded) {
                  this.collapse();
                  this.showingInfoIcon = true;
              } else {
                  this.expand();
                  this.showingInfoIcon = false;
              }
          },
          addEventListeners () {
              this.$refs.btnToggle.addEventListener('click', this.toggle);
          },
          applyAnimation ({expand}) {
              this.$refs.box.classList.remove('box--expanded');
              this.$refs.box.classList.remove('box--collapsed');
              this.$refs.infoBoxContent.classList.remove('info-box-content--expanded');
              this.$refs.infoBoxContent.classList.remove('info-box-content--collapsed');

              // Force a recalc styles here so the classes take hold.
              window.getComputedStyle(this.$refs.box).transform;

              if (expand) {
                  this.$refs.box.classList.add('box--expanded');
                  this.$refs.infoBoxContent.classList.add('info-box-content--expanded');
              } else {
                  this.$refs.box.classList.add('box--collapsed');
                  this.$refs.infoBoxContent.classList.add('info-box-content--collapsed');
              }
          },
          calculateScales () {
              const collapsed = this.$refs.infoxBoxIcon.getBoundingClientRect();
              const expanded = this.$refs.box.getBoundingClientRect();

              this.collapsed = {
                  x: collapsed.width / expanded.width,
                  y: collapsed.height / expanded.height
              }
          },
          createEaseAnimations () {
              let menuEase = document.querySelector('.box-ease');
              if (menuEase) {
                  return menuEase;
              }

              menuEase = document.createElement('style');
              menuEase.classList.add('box-ease');

              const menuExpandAnimation = [];
              const menuExpandContentsAnimation = [];
              const menuCollapseAnimation = [];
              const menuCollapseContentsAnimation = [];
              for (let i = 0; i <= 100; i++) {
                  const step = this.ease(i/100);
                  const startX = this.collapsed.x;
                  const startY = this.collapsed.y;
                  const endX = 1;
                  const endY = 1;

                  // Expand animation.
                  this.append({
                      i,
                      step,
                      startX: this.collapsed.x,
                      startY: this.collapsed.y,
                      endX: 1,
                      endY: 1,
                      outerAnimation: menuExpandAnimation,
                      innerAnimation: menuExpandContentsAnimation
                  });

                  // Collapse animation.
                  this.append({
                      i,
                      step,
                      startX: 1,
                      startY: 1,
                      endX: this.collapsed.x,
                      endY: this.collapsed.y,
                      outerAnimation: menuCollapseAnimation,
                      innerAnimation: menuCollapseContentsAnimation
                  });
              }

              menuEase.textContent = `@keyframes menuExpandAnimation {${menuExpandAnimation.join('')}}
              @keyframes menuExpandContentsAnimation {${menuExpandContentsAnimation.join('')}}
              @keyframes menuCollapseAnimation {${menuCollapseAnimation.join('')}}
              @keyframes menuCollapseContentsAnimation {${menuCollapseContentsAnimation.join('')}}`;

              document.head.appendChild(menuEase);
              return menuEase;
          },
          append ({
                       i,
                       step,
                       startX,
                       startY,
                       endX,
                       endY,
                       outerAnimation,
                       innerAnimation}) {

              const xScale = startX + (endX - startX) * step;
              const yScale = startY + (endY - startY) * step;

              const invScaleX = 1 / xScale;
              const invScaleY = 1 / yScale;

              outerAnimation.push(`${i}% {transform: scale(${xScale}, ${yScale});}`);
              innerAnimation.push(`${i}% {transform: scale(${invScaleX}, ${invScaleY});}`);
          },
          clamp (value, min, max) {
              return Math.max(min, Math.min(max, value));
          },
          ease (v, pow=4) {
              v = this.clamp(v, 0, 1);
              return 1 - Math.pow(1 - v, pow);
          }
      }
  });
</script>

<template>
    <div class="box" ref="box">
        <div class="info-box-content" ref="infoBoxContent">
            <button class="btn-toggle" ref="btnToggle">
                <span class="info-box-icon" ref="infoxBoxIcon">
                    <svg v-if="showingInfoIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-info-square" viewBox="0 0 16 16">
                        <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                    </svg>
                    <svg v-else class="close-modal" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <path
                        d="M24 20.188l-8.315-8.209 8.2-8.282-3.697-3.697-8.212 8.318-8.31-8.203-3.666 3.666 8.321 8.24-8.206 8.313 3.666 3.666 8.237-8.318 8.285 8.203z" />
                </svg>
                </span>
            </button>
            <div class="info-box-items" ref="infoBoxText">
                <h2 class="info-box-item">{{title}}</h2>
                <hr class="info-box-item separator">
                <p class="info-box-item" v-html="text"></p>
            </div>
        </div>
    </div>
</template>

<style scoped lang="scss">

.box {
    opacity: 0;
    pointer-events: none;
    transform-origin: top right;
    overflow: hidden;
    will-change: transform;
    background: $beige;
    box-shadow: $dark-beige 5px 5px 0;
    max-width: 400px;
}

.info-box-content {
    transform-origin: top right;
    will-change: transform;
}

.btn-toggle {
    text-align: right;
    padding: 0;
    margin: 0;
    border: none;
    background: none;
    cursor: pointer;
    width: 100%;
}

.info-box-icon {
    padding: 10px;
    outline: none;
    margin: 0;
    display: inline-flex;
}

.info-box-items {
    position: relative;
    list-style: none;
    margin: 0;
    z-index: 1;
}

h2 {
    text-align: center;
}

.separator {
    width: 25%;
    height: 1px;
    border-width: 0;
    color: $dark-purple;
    background-color: $dark-purple;
}

p {
    text-align: justify;
    margin: 5px 20px 40px;
    padding: 10px;
}

.close-modal {
    margin: 10px;
}

.box--active {
    opacity: 1;
    pointer-events: auto;
}

.box--expanded {
    animation-name: menuExpandAnimation;
    animation-duration: 0.2s;
    animation-timing-function: linear;
}

.info-box-content--expanded {
  animation-name: menuExpandContentsAnimation;
  animation-duration: 0.2s;
  animation-timing-function: linear;
}

.box--collapsed {
  animation-name: menuCollapseAnimation;
  animation-duration: 0.2s;
  animation-timing-function: linear;
}

.info-box-content--collapsed {
  animation-name: menuCollapseContentsAnimation;
  animation-duration: 0.2s;
  animation-timing-function: linear;
}

</style>