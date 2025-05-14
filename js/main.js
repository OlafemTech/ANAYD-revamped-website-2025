document.addEventListener("DOMContentLoaded", function () {
  // Initialize Charts
  const projectDistributionCtx = document.getElementById('projectDistributionChart')?.getContext('2d');
  const yearlyProgressCtx = document.getElementById('yearlyProgressChart')?.getContext('2d');

  // Initialize dropdowns
  const readMoreButtons = document.querySelectorAll('.read-more');
  readMoreButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const targetId = this.getAttribute('href').substring(1);
      const details = document.getElementById(targetId);
      if (details) {
        const isHidden = details.classList.contains('hidden');
        // First set display to block before removing hidden class for smooth transition
        if (isHidden) {
          details.style.display = 'block';
          setTimeout(() => details.classList.remove('hidden'), 10);
        } else {
          details.classList.add('hidden');
          setTimeout(() => details.style.display = 'none', 300); // Match transition duration
        }
        const icon = this.querySelector('i');
        if (icon) {
          icon.classList.toggle('ri-arrow-right-line');
          icon.classList.toggle('ri-arrow-down-line');
        }
      }
    });
  });

  // Hide all project details initially
  document.querySelectorAll('.project-details').forEach(section => {
    section.classList.add('hidden');
    section.style.display = 'none';
  });

  // Project Distribution Chart
  if (projectDistributionCtx) {
    new Chart(projectDistributionCtx, {
      type: 'doughnut',
      data: {
        labels: ['HIV/AIDS', 'TB', 'Malaria', 'GBV', 'COVID-19', 'SRHR'],
        datasets: [{
          data: [30, 15, 20, 15, 10, 10],
          backgroundColor: [
            '#4285F4',  // Blue
            '#FBBC05',  // Yellow
            '#34A853',  // Green
            '#EA4335',  // Red
            '#673AB7',  // Purple
            '#E91E63'   // Pink
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              padding: 20,
              font: {
                family: "'Open Sans', sans-serif",
                size: 12
              }
            }
          }
        },
        cutout: '70%',
        animation: {
          animateScale: true,
          animateRotate: true
        }
      }
    });
  }

  // Yearly Progress Chart
  if (yearlyProgressCtx) {
    new Chart(yearlyProgressCtx, {
      type: 'bar',
      data: {
        labels: ['2017', '2018', '2019', '2020', '2021', '2022', '2023', '2024'],
        datasets: [{
          label: 'People Impacted',
          data: [25000, 45000, 75000, 95000, 125000, 165000, 195000, 256000],
          backgroundColor: '#4285F4',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              display: true,
              color: 'rgba(0, 0, 0, 0.1)'
            },
            ticks: {
              font: {
                family: "'Open Sans', sans-serif"
              }
            }
          },
          x: {
            grid: {
              display: false
            },
            ticks: {
              font: {
                family: "'Open Sans', sans-serif"
              }
            }
          }
        },
        animation: {
          duration: 2000,
          easing: 'easeInOutQuart'
        }
      }
    });
  }

  // Image slider functionality
  let currentSlide = 0;
  const slides = document.querySelectorAll('.slide');
  
  function showSlide(index) {
    slides.forEach(slide => slide.style.opacity = '0');
    slides[index].style.opacity = '1';
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  // Show first slide
  if (slides.length > 0) {
    showSlide(0);
  }

  // Auto advance slides
  setInterval(nextSlide, 5000);
});
