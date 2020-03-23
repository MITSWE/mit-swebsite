//this file renders all members

var members_info = [];
var member_names = ['Madie Wang', 'Christina Patterson', 'Amanda Hu', 'Varnika Sinha', 'Valerie Chen', 'Nicole Munne', 'Amber Li', 'Nghi Nguyen', 'Tanya Yang', 'Lucy Wang', 'Jianna Liu', 'Malobika Syed', 'Naksha Roy', 'Sarah Lohmar', 'Koumani Ntowe', 'Megan Wei', 'Grace Zheng', 'Laurena Huh', 'Mindy Long', 'Jocelin Su', 'Jessica Sun', 'Anna Sun', 'Aliza Rabinovitz', 'Jeana Choi', 'Julia Jia', 'Lauren Oh', 'Seung Hyeon Shim', 'Shruti Ravikumar', 'Kelly Ho', 'Tejal Reddy', 'Jamie Geng', 'Elaine Xiao', 'Fiona Cai', 'Varsha Sandadi', 'Niki Kim', 'Nithya Attaluri', 'Shirley Cao', 'Rovi Porter', 'Meenakshi Singh', 'Haijia Wang', 'Nicole Cybul', 'Tiffany Trinh', 'Anna Wong'];
var member_courses = ['6-3', '2A-20', '20', '6-3', '6-2', '10', '6-3', '6-3', '6-3', '6-3', '6-3', '6-3', '10-ENG, Environment', '11-6', '20', '6-3', '18, 15-2', '6-14, 15-3', '6-3, 18', '6-3, 18', '6-7', '6-3', '6-3', '6-2', '6-2', '6-3', '20', '6-9', '6-2', '6-3', '6-2', '6-3', '6,9', '20', '6-3', '6-3', '6-3', '1', '6-2', '6-2', '20', '6-2', '6-3'];
var member_class_year = [2022, 2023, 2023, 2022, 2022, 2022, 2022, 2023, 2022, 2021, 2023, 2023, 2022, 2022, 2021, 2022, 2022, 2023, 2023, 2023, 2021, 2023, 2023, 2021, 2022, 2021, 2023, 2023, 2022, 2023, 2022, 2022, 2023, 2023, 2023, 2023, 2022, 2022, 2023, 2023, 2023, 2022, 2022];
var fun_facts = ['I was born left handed but trained to be right handed.', 'I have traveled to over 20 countries.', "I'm on the MIT fencing team!", 'I really love chocolate, and once ate 3 pounds of it.', "I'm easily recognizable by the big silver object (my cello) often accompanying me.", 'I know Morse code.', 'I have legally changed my name before.', 'My name is pronounced like "Nee"-- or Knee.', "My name is a combination of my parent's last names - Tan and Yang!", 'I like doing calligraphy and chalk art.', 'I love to bake and dance!', 'I studied abroad in France for a term.', 'I can speak English, Bengali, Hindi and Spanish!', 'I enjoy photography!', 'I have over 30 first cousins.', 'I own a pair of floral crocs.', 'I love music festivals! Ultra is next!', 'I love eating spicy food!', 'I love mountains!', 'I can fold origami roses.', 'I love doing the NYT crossword!', "There's a song named after me by Walk the Moon.", 'I love walking around and exploring cities!', "I run on Dunkin' every morning!", 'I like to crochet!', 'I type with caps lock instead of shift.', 'I love painting, eating chocolate, and "color" transparent.', 'I love being in airports!', "I've watched every episode of Friends.", 'I love hiking!', 'Black sesame makes the best drink.', 'I painted a mural in the MIT tunnels!', "I'm an avid player of KenKen, and I love fruit snacks.", 'I love eating ice cream!', 'My first name is Nicole and last name is Kim, but I go by the first two letters of my first and last name.', 'I love to sing!', 'I enjoy looking at pretty journals!', 'I went to three continents in one summer.', 'I love flowers and my favorite is the cherry blossom!', 'I am from NJ.', 'I have played soccer in 20 states and 5 different countries!', 'I was voted Advanced Procrastinator in high school!', 'I taught in Spain for a month!'];

var departments = [];

function store_info_department() {
    var department = new Object();
    department.name = "EXECUTIVE TEAM";
    department.positions = {"Presidents": ["Jeana Choi", "Koumani Ntowe"],
    "Financial Officer": ["Jocelyn Shen", "Lisa Yoo"],
    "VP Off-Campus Outreach": ["Sabrina Liu"],
    "VP On-Campus Outreach": ["Camellia Huang"],
    "VP Campus Relations": ["Shobhita Sundaran"],
    "VP Membership": ["Hannah Liu"],
    "VP Career Development": ["Valerie Chen"],
    "VP Technology": ["Shushu Fang"]};


    departments.push(department);

    department = new Object();
    department.name = "OFF-CAMPUS OUTREACH";
    department.positions = {'Innovation Chairs': ['Emma Liu', 'Fiona Cai'], 'School Off-Campus Chairs': ['Sylvia Waft', 'Naksha Roy', 'Ava Pettit', 'Nicole Cybul', 'Niki Kim'], 'Festival Chairs': ['Madeline Wang', 'Meenakshi Singh'], 'High School Off-Campus Chairs': ['Annie Zhang', 'Kriti Jain'], 'SWETube Chairs': ['Julia Jia', 'Britney Ting', 'Haija Wang']};
    departments.push(department);

    department = new Object();
    department.name = "ON-CAMPUS OUTREACH";
    department.positions = {'WiSE Chairs': ['Shruti Ravikumar', 'Anushka Nair'], 'Girl Scouts Chairs': ['Anna Wong', 'Aliza Rabinovitz'], 'KEYS Chairs': ['Varsha Sandadi', 'Fiona Gillespie'], 'High School On-Campus Chairs': ['Sarah Lohmar', 'Rovi Porter'], '#HelloWorld Chairs': ['Tiffany Trinh', 'Varnika Sinha'], 'STEM Exploration Chairs': ['Seung Hyeon Shim', 'Amanda Hu']};
    departments.push(department);

    department = new Object();
    department.name = "CAMPUS RELATIONS";
    department.positions = {'Campus Representative Chairs': ['Malobika Syed', 'Amelia Meles'], 'Department Liaison Chairs': ['Grace Zheng', 'Nithya Attaluri'], 'Marketing and Media Chairs': ['Elaine Xiao', 'Alicia Guo'], 'SWETube Chairs': ['Julia Jia', 'Britney Ting', 'Haija Wang'], 'SWETube Video Editor Chair': ['Nghi Nguyen']};

    departments.push(department);

    department = new Object();
    department.name = "MEMBERSHIP";
    department.positions = {'Internal Mentorship Chairs': ['Shirley Cao', 'Jamie Geng'], 'Membership Development Chairs': ['Lauren Oh', 'Jessica Sun'], 'External Relations Chairs': ['Mindy Long', 'Christina Patterson']};
    departments.push(department);

    department = new Object();
    department.name = "CAREER DEVELOPMENT";
    department.positions = {'Career Development Chairs': ['Nicole Munne', 'Lucy Wang', 'Kelly Wu', 'Tejal Reddy', 'Megan Wei', 'Laurena Huh', 'Anna Sun'], 'Alumni Relations Chairs': ['Tanya Yang', 'Kelly Ho']};
    departments.push(department);

    department = new Object();
    department.name = "TECHNOLOGY";
    department.positions = {'Technology Chairs': ['Amber Li', 'Adrianna Wojtyna'], 'Web Chair': ['Jocelin Su']};
    departments.push(department);

}
function store_info_members() {
    for (var i=0; i<member_names.length; i++) {
        var current_member = new Object();
        current_member.full_name = member_names[i];
        current_member.photo_url = "../sp2020_board_pics/" + current_member.full_name.replace(/\s+/g, '') + ".jpg";
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
       person_object.course = '';
       person_object.class_year = 2023;
       person_object.fun_fact = '';
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
