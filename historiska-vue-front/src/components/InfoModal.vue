<template>
    <div class="inner">
      <h2>This is a modal window!</h2>
      <p>You have done such great work! Congratulations on reaching this step!</p>
    </div>
  </template>
  
  <script setup lang="ts"></script>
  
  <style scoped>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }
  .inner {
    background-color: white;
    padding: 1rem;
  
    border-radius: 1rem;
  
    display: flex;
    flex-direction: column;
    gap: 1.4rem;
  }
  
  .inner > h2 {
    font-size: 3rem;
    font-weight: bold;
  }
  
  .inner > p {
    font-size: 1.8rem;
    font-weight: 300;
  }
  </style>