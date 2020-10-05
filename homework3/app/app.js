var students = {
  Student: [
    {
      name: "Stewart",
      age: "27",
      phoneNum: "317-555-5555",
      email: "smccalle@iu.edu",
      classes: ["I421", "N423", "N322", "CIT444"],
    },
  ],
};

$("#addStudent").click(function () {
  let studentName = $("#studentName").val();
  let studentAge = $("#studentAge").val();
  let studentPhone = $("#studentPhoneNum").val();
  let studentEmail = $("#studentEmail").val();
  let classes = $("#studentClasses").val().split(" ");

  if (!localStorage.getItem("storedStudents")) {
  } else {
    students = JSON.parse(localStorage.getItem("storedStudents"));
  }

  students.Student.push({
    name: studentName,
    age: studentAge,
    phoneNum: studentPhone,
    email: studentEmail,
    classes: classes,
  });

  localStorage.setItem("storedStudents", JSON.stringify(students));
  console.log(students);
});

$("#displayStudents").click(function () {
  if (!localStorage.getItem("storedStudents")) {
    $(".content").html("There are no students saved");
  } else {
    students = JSON.parse(localStorage.getItem("storedStudents"));
    displayStudents(students);
  }
});

function displayStudents(students) {
  $(".content").empty();
  $.each(students.Student, function (idx, Student) {
    console.log(Student);
    $(".content").append(
      `<div>
                <h3>${Student.name}</h3>
                <p>Age: ${Student.age}</p>
                <p>Phone#: ${Student.phoneNum}</p>
                <p>Email: ${Student.email}</p>
                <p>Classes: ${Student.classes}</p>
            </div>`
    );
  });
}

/* <ul>
                
                ${$.each(Student.classes, function (idx, classes) {
                  $("ul").append(
                    ` <li>
                         ${classes}
                         </li>`
                  );
                })}
                 
                 </ul> */
