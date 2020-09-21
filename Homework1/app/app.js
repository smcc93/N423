var apiKey = "4ee9a468e68146de9f6195752201409";

var baseUrl = `https://api.weatherapi.com/v1/current.json?key=${apiKey}&q=`;

var forecastUrl = `https://api.weatherapi.com/v1/forecast.json?key=${apiKey}&q=`;

var timezoneUrl = `https://api.weatherapi.com/v1/timezone.json?key=${apiKey}&q=`;

var forecastDays = `&days=3`;

function getData(fullURL) {
  $.get(fullURL, function (data) {
    $(".content").html(
      `
      <div>
      <p>City Name: ${data.location.name}</p>
          <p>State: ${data.location.region}</p>
          <p>Country: ${data.location.country}</p>
          <p>Current Time: ${data.location.localtime}</p>
          <p>Last Updated: ${data.current.last_updated}</p>
          <img src=" ${data.current.condition.icon}" height="200" width="200">
          <p>Condition: ${data.current.condition.text}</p>
          <p>Precipitation: ${data.current.precip_in}</p>
          <p>Temperature (F): ${data.current.temp_f} </p>
          <p>Feels like: ${data.current.feelslike_f}</p>
          <p>Temperature (C): ${data.current.temp_c} </p>
          <p>Feels like: ${data.current.feelslike_c}</p>
          <p>Humidity: ${data.current.humidity}</p>
          <p>Wind (mph): ${data.current.wind_mph} mph</p>
          <p>Wind (kph): ${data.current.wind_kph} kph</p>
          <p>Wind direction: ${data.current.wind_degree} ${data.current.wind_dir}</p>
          </div>
          `
    );
  }).catch(function (error) {
    alert("invalid zipcode");
  });
}

function forecastLoop(data) {
  $.each(data.forecast.forecastday, function (idx, forecastday) {
    console.log(forecastday);
    $(".content").append(
      `<div>
      <p>Date: ${forecastday.date}</p>
      <img src=" ${forecastday.day.condition.icon}" height="200" width="200">
      <p> Condition: ${forecastday.day.condition.text}</p>
      <p>Chance of rain: ${forecastday.day.daily_chance_of_rain} %</p>      
        <p> Max Temp (F): ${forecastday.day.maxtemp_f}</p>
        <p>Max Temp (C): ${forecastday.day.maxtemp_c}</p>
        <p>Min Temp (F): ${forecastday.day.mintemp_f}</p>
        <p>Min Temp (C): ${forecastday.day.mintemp_c}</p>
        <p>Average Humidity: ${forecastday.day.avghumidity}</p>
        <p>Max Wind (mph): ${forecastday.day.maxwind_mph}</p>
        <p>Max Wind (kph): ${forecastday.day.maxwind_kph}</p>
        
        </div>
        `
    );
  });
}

function getForecast(fullForecastURL) {
  $.get(fullForecastURL, function (data) {
    $(".content").append(
      `
      <div class="currentday">
           <p>Current Time: ${data.location.localtime}</p>
          <img src=" ${data.current.condition.icon}" height="200" width="200">
          <p>Condition: ${data.current.condition.text}</p>
          <p>Precipitation: ${data.current.precip_in}</p>
          <p>Temperature (F): ${data.current.temp_f} </p>
          <p>Feels like: ${data.current.feelslike_f}</p>
          <p>Temperature (C): ${data.current.temp_c} </p>
          <p>Feels like: ${data.current.feelslike_c}</p>
          <p>Humidity: ${data.current.humidity}</p>
          <p>Wind (mph): ${data.current.wind_mph} mph</p>
          <p>Wind (kph): ${data.current.wind_kph} kph</p>
          <p>Wind direction: ${data.current.wind_degree} ${data.current.wind_dir}</p>
          </div>
          `
    );

    forecastLoop(data);
  }).catch(function (error) {
    alert("invalid zipcode");
  });
}

function getTimezone(fullTimezoneURL) {
  $.get(fullTimezoneURL, function (data) {
    $(".content").append(
      `
      <div class="locationInfo">      
            <p>Location: ${data.location.name}</p>
            <p>Region: ${data.location.region}</p>
            <p>Country: ${data.location.country}</p>
            <p>Latitude: ${data.location.lat} </p>
            <p>Longitude: ${data.location.lon}</p>
            <p>Timezone: ${data.location.tz_id} </p>
            <p>Time: ${data.location.localtime}</p>
            </div>
            `
    );
  }).catch(function (error) {
    alert("invalid zipcode");
  });
}

function initListeners() {
  $("#getWeather").click(function () {
    var zip = $("#zipcode").val();
    var fullURL = baseUrl + zip;
    console.log(fullURL);

    getData(fullURL);
  });

  $("#getForecast").click(function () {
    var forecastzip = $("#forecastzip").val();
    var fullForecastURL = forecastUrl + forecastzip + forecastDays;
    console.log(forecastzip);
    getForecast(fullForecastURL);
  });

  $("#getTimezone").click(function () {
    var timezonezip = $("#timezonezip").val();
    var fullTimezoneURL = timezoneUrl + timezonezip;
    getTimezone(fullTimezoneURL);
  });

  $("div a").click(function (e) {
    var id = e.currentTarget.id;

    $("#homeVisible").addClass("hide");
    $(".content").removeClass("hide");
    if (id == "weather") {
      $(".zipInput").removeClass("hide");
      $(".forecastInput").addClass("hide");
      $(".timezoneInput").addClass("hide");
      $(".content").empty();
    }
    if (id == "forecast") {
      $(".forecastInput").removeClass("hide");
      $(".timezoneInput").addClass("hide");
      $(".zipInput").addClass("hide");
      $(".content").empty();
    }
    if (id == "timezone") {
      $(".timezoneInput").removeClass("hide");
      $(".forecastInput").addClass("hide");
      $(".zipInput").addClass("hide");
      $(".content").empty();
    }
    if (id == "home") {
      $("#homeVisible").removeClass("hide");
      $(".forecastInput").addClass("hide");
      $(".zipInput").addClass("hide");
      $(".timezoneInput").addClass("hide");
      $(".content").addClass("hide");
    }
  });
}

$(document).ready(function () {
  initListeners();
});
