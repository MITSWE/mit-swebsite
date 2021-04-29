//this file renders all members

var members_info = [];

var member_names = ['Valerie Chen', 'Sophie Lockton', 'Sabrina Liu', 'Irene Huang',
'Helena Merker', 'Angelina Zhang', 'Christina Patterson', 'Jimin Lee', 'Emma Liu',
'Megan Wei', 'Joyce Yuan', 'Helen Hu', 'Anna Wong', 'Ashley Granquist', 'Valerie Yuen',
'Eugenia Feng', 'Helen Yang', 'Haeri Kim', 'Elissa Ito', 'Hana Ro', 'Kelly Ho',
'Mindy Long', 'Zoe Wong', 'Fiona Cai', 'Camellia Huang', 'Haijia Wang', 'Amelia Meles',
'Sylvie Waft', 'Hannah Kim', 'Sadhana Lolla', 'Shushu Fang', 'Sarah Lohmar', 'Radha Patel',
'Shirley Cao', 'Joli Dou', 'Nicole Cybul', 'Adrianna Wojtyna', 'Eileen Li', 'Haeri Kim', 
'Niki Kim', "Madie Wang", 'Anushka Nair', 'Seung Hyeon Shim', 'Nithya Attaluri', 'Eileen Ito',
'Sophie Zhang'];

var member_courses = ['6-2', '6-3', '20', '6-3', '6-3', '6-3', '2,21M', '6-2', '6-3',
'6-3, 15-2', '6-3, 18', '18, 6-3', '6-3, 18', '6-3', '18, 6-3', '6-3', '6-3, 9', '2',
'2A', '2', '6-2', '6-3', '6-2', '6-3', '20', '6-3', '6-3', '2', '6-3', '6-3, 18', '6-3',
'11-6', '6-3', '6-3', '6-3', '6-3', '6-2', '6-3', '2', '6-2', "6-3", '6-3', '20', '6-3', "-",
"-"];

var member_class_year = ['2022', '2024', '2024', '2024', '2024', '2023', '2023', '2024',
'2022', '2022', '2024', '2024', '2022', '2024', '2024', '2024', '2024', '2024', '2023',
'2024', '2022', '2023', '2023', '2023', '2022', '2023', '2022', '2023', '2024', '2024',
'2022', '2022', '2024', '2022', '2024', '2023', '2023', '2024', '2024', '2023', '2022', '2023',
'2023', '2023', "-", "-"];

var fun_facts = ['I\'m easily recognizable by the big silver object (my cello) often accompanying me.',
'I\'ve been playing the harp for 10 years!',
'I\'ve been trying to learn to play the guitar!',
'I lovee peanut butter and shopping at Costco or any grocery store! :)',
'I like cute puppies.',
'I recently learned how to ride a bike!',
'I love playing the piano and watching 80s movies.',
'I love being outdoors!',
'I love Star Wars!',
'I spent 2020 New Years on a train.',
'I have the same birthdate as Spiderman/Peter Parker in the Marvel universe :^)',
'I\'ve had cactus flavored gelato!',
'I taught in Spain for a month!',
'I enjoy figure skating and playing the violin.',
'I love puzzles and curly fries.',
'I like bursting boba over tapioca pearls!',
'I like to collect plants and stuffed microbes!',
'I\'ve moved ~10 times in my life and am now living in Hawaii! (when not on campus)',
'I\'m on the sailing team!',
'My favorite ice cream is cookies \'n cream :)',
'I like raspberry lemonade',
'I like spicy food!',
'I love learning languages!',
'My favorite fruit is watermelon.', 
'I lived in Taiwan for 2 years!',
'I like to try new foods!',
'I have a pet corgi!',
'I love calligraphy and baking!',
'My favorite boba flavor is wintermelon :)',
'I once baked and delivered fifty cupcakes',
'I bookmarked MIT confession page.',
'I want to live in another country some day!',
'I am a die-hard Harry Potter fan!',
'I really like plushies and stationery!!!',
'I can draw a frog in 5 seconds.',
'My record for juggling a soccer ball is over 1,500!',
'I think silver nanoparticles are super cool and I love making them.',
'I love spicy food :)',
'Despite having a fear of heights, I really want to go skydiving one day!',
'I love going on adventures and trying new activities',
'I was born left handed but trained to be right handed.',
'I love SWE!',
'I love painting, eating chocolate, and "color" transparent.',
'I love to sing!',
'I love SWE!',
'I love SWE!'];

var departments = [];

function store_info_department() {
    var department = new Object();
    department.name = "EXECUTIVE TEAM";
    department.positions = {"President": ["Camellia Huang"],
    "Financial Officer": ["Madie Wang"],
    "Administrative Officer": ["Amelia Meles"],
    "VP Outreach": ["Anna Wong", "Sarah Lohmar"],
    "VP Campus Relations": ["Haijia Wang"],
    "VP Membership": ["Shirley Cao"],
    "VP Career Development": ["Valerie Chen"],
    "VP Technology": ["Shushu Fang"]};


    departments.push(department);

    department = new Object();
    department.name = "OUTREACH";
    department.positions = {'College Connections': ['Valerie Yuen', 'Zoe Wong'], 'Engineering Kits': ['Fiona Cai', 'Niki Kim', 'Hana Ro'], 'Festival Chairs': ['Emma Liu', 'Helen Yang'], '#HelloWorld': ['Nicole Cybul', 'Joyce Yuan'], 'Outreach Seminar Series': ['Ashley Granquist', 'Sophie Lockton'], 'STEAM Saturdays': ['Sylvie Waft', 'Seung Hyeon Shim'], "SWE Course": ['Sabrina Liu', 'Anushka Nair']};
    departments.push(department);


    department = new Object();
    department.name = "CAMPUS RELATIONS";
    department.positions = {'Campus Representatives': ['Joli Dou', 'Nithya Attaluri'], 'Department Liaison Chairs': ['Eileen Ito', 'Kelly Ho'], 'Marketing and Media Chairs': ['Helena Merker', 'Sophie Zhang']};

    departments.push(department);

    department = new Object();
    department.name = "MEMBERSHIP";
    department.positions = {'Internal Mentorship Chairs': ['Christina Patterson', 'Elissa Ito'], 'Membership Development Chairs': ['Haeri Kim', 'Mindy Long'], 'External Relations Chairs': ['Angelina Zhang']};
    departments.push(department);

    department = new Object();
    department.name = "CAREER DEVELOPMENT";
    department.positions = {'Career Development Chairs': ['Eugenia Feng', 'Sadhana Lolla', 'Irene Huang', 'Megan Wei'], 'Corporate/Alumni Relations Chairs': ['Radha Patel', 'Jimin Lee']};
    departments.push(department);

    department = new Object();
    department.name = "TECHNOLOGY";
    department.positions = {'Technology Chairs': ['Helen Hu', 'Adrianna Wojtyna'], 'Web Chair': ['Hannah Kim']};
    departments.push(department);

}
function store_info_members() {
    for (var i=0; i<member_names.length; i++) {
        var current_member = new Object();
        current_member.full_name = member_names[i];
        current_member.photo_url = "../2021_board_pics/" + current_member.full_name.replace(/\s+/g, '_') + ".jpg";
        current_member.course = member_courses[i];
        current_member.class_year = member_class_year[i];
        current_member.fun_facts = fun_facts[i];
        members_info.push(current_member);
    }

}

function find_person(person_name) {
    for (var i in members_info) {
        if (members_info[i].full_name == person_name) {
            return members_info[i];
        }
    }
    return "NOT FOUND";
}

counter = 0;
var row_wrapper = document.createElement("div");
row_wrapper.className = "row";

function build_person_html(position, person) {
    var person_object = find_person(person);
    if (person_object == "NOT FOUND") {
       console.log(person);
       person_object = new Object();
       person_object.full_name = person;
       person_object.photo_url = 'placeholder.jpg';
       person_object.course = '--';
       person_object.class_year = '--';
       person_object.fun_facts = '--';
     }
    //else {
       counter += 1;
       if (counter==5) { //reset row wrapper if reaching the end of row. we keep 4 per rows
           document.getElementById("container").append(row_wrapper);
           counter = 1;
           row_wrapper = document.createElement("div");
           row_wrapper.className = "row";
       }
       //member-container div wraps around image, text and description
       var center = document.createElement("div");
       center.className = "col-md-3 col-sm-3";
       center.align = "center";
       //add image
       var member_container = document.createElement("div");
       member_container.className = "member-container";

       var member_image = document.createElement("img");
       member_image.src = person_object.photo_url;
       member_image.className = "member-image";
       member_image.alt = "";
       member_image.style="width:200px;height:200px;object-fit:cover;image-orientation:from-image;"

       member_container.appendChild(member_image);
       //add text
       var over_lay = document.createElement("div");
       over_lay.className = "member-overlay";
       var member_text = document.createElement("div");
       member_text.className = "member-text";

       var div1 = document.createElement("div");
       var strong1 = document.createElement("strong");
       strong1.style = "color: white;"
       strong1.innerHTML = "Course " + person_object.course + "<br />" + "Class of " + person_object.class_year;
       div1.appendChild(strong1);

       var div2 = document.createElement("div");
       var strong2 = document.createElement("strong");
       strong2.style = "color: white;"
       strong2.innerHTML = "Fun fact: ";// + person_object.fun_facts;

       div2.appendChild(strong2);
       div2.innerHTML += person_object.fun_facts;

       member_text.appendChild(div1);
       member_text.appendChild(document.createElement("br"));
       member_text.appendChild(div2);
       over_lay.appendChild(member_text);

       //add description
       var member_info_div = document.createElement("div");
       member_info_div.className = "member-info";
       var strong = document.createElement("strong");
       strong.innerHTML = person_object.full_name;
       var div_pos = document.createElement("div");
       div_pos.className = "board-pos";
       div_pos.innerHTML = position;
       member_info_div.appendChild(strong);
       member_info_div.appendChild(div_pos);
       member_info_div.style="width:200px;height:63px;"

       member_container.appendChild(over_lay);
       member_container.appendChild(member_info_div);
       center.appendChild(member_container);

       row_wrapper.appendChild(center);
}
function build_html() {
    for (var i = 0; i<departments.length; i++) {
         var header = document.createElement("h2");
         header.innerHTML = departments[i].name;
         document.getElementById("container").append(header);

         counter = 0;
         row_wrapper = document.createElement("div");
         row_wrapper.className = "row";
         for (const [position, people] of Object.entries(departments[i].positions)) {
             for (const person of people) {
                 build_person_html(position, person);
             }
         } //end of building for each individual
         if (counter % 5 != 0)
            document.getElementById("container").append(row_wrapper);
    }
}


function main() {
    store_info_department();
    store_info_members();
    build_html();
}

main();
