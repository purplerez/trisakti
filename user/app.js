

const calendar = document.querySelector('.calendar');
const month_names = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

const isLeapYear = (year) => {
  return (year % 4 === 0 && year % 100 !== 0 && year % 400 !== 0) || (year % 100 === 0 && year % 400 === 0);
};

const getFebDays = (year) => {
  return isLeapYear(year) ? 29 : 28;
};

const generateCalendar = (month, year) => {
  const calendar_days = calendar.querySelector('.calendar-days');
  const calendar_header_year = calendar.querySelector('#year');
  const days_of_month = [31, getFebDays(year), 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

  calendar_days.innerHTML = '';

  const currDate = new Date();
  if (month === undefined) month = currDate.getMonth();
  if (year === undefined) year = currDate.getFullYear();

  const curr_month = `${month_names[month]}`;
  month_picker.innerHTML = curr_month;
  calendar_header_year.innerHTML = year;

  // Get first day of month
  const first_day = new Date(year, month, 1);

  for (let i = 0; i <= days_of_month[month] + first_day.getDay() - 1; i++) {
    const day = document.createElement('div');
    if (i >= first_day.getDay()) {
      day.classList.add('calendar-day-hover');
      day.innerHTML = i - first_day.getDay() + 1;
      day.innerHTML += `<span></span>
                        <span></span>
                        <span></span>
                        <span></span>`;

      if (i - first_day.getDay() + 1 === currDate.getDate() && year === currDate.getFullYear() && month === currDate.getMonth()) {
        day.classList.add('current-date');
      }
    }
    calendar_days.appendChild(day);
  }
};

const month_list = calendar.querySelector('.month-list');

month_names.forEach((e, index) => {
  const month = document.createElement('div');
  month.innerHTML = `<div data-month="${index}">${e}</div>`;
  month.querySelector('div').onclick = () => {
    if (!month.classList.contains('current-date')) {
      month_list.classList.remove('show');
      curr_month.value = index;
      generateCalendar(index, curr_year.value);
    }
  };
  month_list.appendChild(month);
});

const month_picker = calendar.querySelector('#month-picker');

month_picker.onclick = () => {
  month_list.classList.add('show');
};

const currDate = new Date();

const curr_month = { value: currDate.getMonth() };
const curr_year = { value: currDate.getFullYear() };

generateCalendar(curr_month.value, curr_year.value);

document.querySelector('#prev-year').onclick = () => {
  --curr_year.value;
  generateCalendar(curr_month.value, curr_year.value);
};

document.querySelector('#next-year').onclick = () => {
  ++curr_year.value;
  generateCalendar(curr_month.value, curr_year.value);
};

document.addEventListener("DOMContentLoaded", function() {
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Red', 'Blue', 'Yellow'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3],
        backgroundColor: [
          'rgba(255, 99, 132, 0.2)',
          'rgba(54, 162, 235, 0.2)',
          'rgba(255, 206, 86, 0.2)'
        ],
        borderColor: [
          'rgba(255, 99, 132, 1)',
          'rgba(54, 162, 235, 1)',
          'rgba(255, 206, 86, 1)'
        ],
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
});

