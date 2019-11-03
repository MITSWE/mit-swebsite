//this file renders all members

var members_info = [];
var member_names = ['Portia Gaitskell', 'Veronica Perdomo', 'Adrianna Wojtyna', 'Sylvia Waft', 'Amanda Hu', 'Mulan Jiang', 'Cami Mejia', 'Sohini Kar', 'Elaine Xiao', 'Jocelyn Shen', 'Crista Falk', 'Isabella Pedraza Pineros', 'Christina Patterson', 'Naksha Roy', 'Elaine Wen', 'Jocelin Su', 'Jianna Liu', 'Jenny Zhang', 'Haijia Wang', 'Hannah Liu', 'Lili Sun', 'Jocelyn Yao', 'Grace Kim', 'Megan Wei', 'Shruti Ravikumar', 'Jamie Geng', 'Daniela Velez', 'Varsha Sandadi', 'Niki Kim', 'Camellia Huang', 'Agni Kumar', 'Aline Vargas', 'Shannon Duffy', 'Shelly Ben-David', 'Emily Zhang', 'Louise Lima', 'Anjalie Kini', 'Tejal Reddy', 'Emma Liu', 'Annie Zhang', 'Vaibhavi Shah', 'Lauren Oh', 'An Jimenez', 'Valerie Chen', 'Grace Zheng', 'Rovi Porter', 'Anna Wong', 'Amelia Meles', 'Fiona Gillespie', 'Julia Jia', 'Kelly Ho', 'Shirley Cao', 'Kriti Jain', 'Kelly Wu', 'Katharina Gschwind', 'Nova Xu', 'Ashley Wang', 'Haripriya Mehta', 'Shushu Fang', 'Priscilla Wong', 'Rebekah Costello', 'Koumani Ntowe', 'Julia Pei', 'Kaitlyn Hennacy', 'Jennifer McCleary', 'Ariana Adames', 'Joyce Feng', 'Margaret Bertoni', 'Stuti Vishwabhan', 'Linnea Rylander', 'Rebecca Grekin', 'Francisca Vasconcelos', 'Rima Rebei', 'Amanda Deng', 'Dory Shen', 'Shana Mathew', 'Sabrina Liu', 'Laura Huang', 'Baula Xu', 'Katherine Wang', 'Celine Qiu', 'Phi Xu', 'Jeana Choi', 'Shobhita Sundaram', 'Lisa Yoo', 'Danica Dong', 'Saumya Rawat'];
var member_courses = ['6-2', '20', '6-2', '2', '20', '2A', 'Undeclared', '6-3', '6-3, 9', '6-3', '6-3', '3, 8', '2', '10', '15, 6', '6-3, 18', '6-3', '6-3', '6-3', '18', '6 or 16', '20', '6-3', '6-3', '20', '6-2', '6-3', 'Undecided', '2A-20', '20', '6-3, 18', '2A-10B', '6-3', '6-3', '6-3', '20', '6-3', '6-3', '6-3', '6-3', '20', '6-3', '6-3', '6-2', '18', '1', '6-3', '6-3', '6-2', '6-3', '6-2', '2A', '6-2, 9', '10', '6-3', '20', '6-14', '6-2', '6-2', '6-3', '20', '20', '20', '10', '6-2', '6-14', '6-3, 14-1', '2', '6-3', '6-3', '10', '6-2, 8', '6-3, 9', '6-3, 15', '6-2', '6-2', '6-2', '2', '6-3', '6-3', '6-2', '6', '6', '', '6-3', '10', '6-3'];

var member_class_year = [2023, 2023, 2023, 2023, 2023, 2023, 2023, 2022, 2022, 2022, 2023, 2023, 2023, 2022, 2023, 2023, 2023, 2021, 2023, 2022, 2023, 2021, 2023, 2022, 2023, 2022, 2023, 2023, 2023, 2022, 2020, 2021, 2020, 2023, 2021, 2023, 2023, 2023, 2022, 2022, 2021, 2021, 2021, 2022, 2022, 2022, 2022, 2022, 2022, 2022, 2022, 2022, 2021, 2022, 2020, 2020, 2021, 2020, 2022, 2020, 2020, 2021, 2020, 2020, 2019, 2022, 2021, 2019, 2020, 2021, 2019, 2021, 2021, 2021, 2020, 2020, 2021, 2019, 2019, 2019, 2021, 2022, 2021, 2022, 2022, 2022, 2022];

var fun_facts = ['I took a gap year after high school and went to Australia!', 'I love to cook! I really enjoy cooking for myself and others. Baking is also so much fun!', 'I think silver nanoparticles are super cool and I love synthesizing them.', 'I love doing calligraphy, playing the cello, and building things!', 'I have been fencing for ten years!', 'I sneeze up to 9 times in a row.', 'I once won AirPods from a Poker Club event.', 'I love painting with gouache!', 'I painted a mural in the MIT tunnels!', 'I write novels in my free time!', 'My first job was as a park ranger for my city government back in Idaho!', 'I love nature! Everything from horseback riding, growing coffee, and gardening.', 'I love jazz and classic musicals. ', 'I can speak 4 languages: English, Bengali, Hindi, and Spanish!', "I've been to both corgi and shiba cafes in China and held an adorable corgi puppy on my lap! ", 'I can fold origami roses!', 'I love baking and dancing to hip hop! ', "I've never broken a bone.", 'I enjoy drawing, baking, and playing tennis in my free time. ', 'I aspire to be a Yerba Ambassador.', 'I (and my robot) was on the Daily Show for 2 seconds. I can also juggle.', 'My minimum nap time is 4 hours.', 'I love to sing and bullet journal!', "I'm a huge fan of super spicy food.", 'I love bullet journaling and listening to true crime podcasts. ', "I'm running my first half marathon on 10/13!", 'I went cliff jumping last summer off of a 35 ft cliff!', 'I enjoy hiking and running!', 'I like to study to oomsk oomsk oomsk music.', 'I lived in Taiwan for 2 years!', 'I take space photos!', 'I love to bike!', 'I trained my fish to follow my finger.', 'I am the oldest of four sisters and love supporting women in STEM!', 'I can write with both hands!', 'I love playing volleyball and exploring the outdoors!', "I'm a life-long Broncos fan!", "I've hiked all the way down the Grand Canyon.", 'I love Star Wars.', 'I like trees.', "I've been to over 30 countries!", 'I got kicked out of preschool.', 'I am a Zumba instructor!', 'You may catch me eating fries with a fork...', 'I have played piano for 10 years!', 'Loves doing the KenKen and Sudoku in MIT weekly newspapers...', 'I love spicy food!', 'I love corgis!', 'I love baking cakes!', 'I can crochet a mini owl!', "I've watched every episode of Friends.", 'I like bullet journaling and calligraphy!', "I'm on MIT Bhangra!", 'Hiking the Swiss alps is on my bucket list.', 'I can speak 6 languages!', 'I have a twin brother with a different birthday!', 'I love Hawaii!', 'Unwittingly, I asked Dropbox CEO Drew Houston if he was a current student at MIT. Oops!', 'I bookmarked MIT confessions submission page.', 'I like to whistle!', 'I never watch Marvel movie trailers to avoid any spoilers!', "I'm left handed.", 'I can fold my tongue into the shape of a three-leaf clover!', ' I love experimenting with desserts and coming up with new recipes.', 'I play League of Legends.', "I have over 30 snow globes of countries across 4 continents of which I've visited!", 'I can say the alphabet quicker backwards than forwards.', "I've traveled to four different continents.", 'I got to see Pink in concert for free!', 'I can ride a unicycle!', 'I went to 12 countries while backpacking in Europe for 2.5 months the summer before college!', "I was born on a Friday the 13th on my mom's birthday.", 'I was in a hair commercial in Thailand!', 'I like trap music.', 'My favorite type of fried potato is waffle fries.', 'I can touch my tongue to my nose!', 'I eat apples whole with the core.', 'I love cheesecake!', "I'm really good at pinching people with my toes!", "I'm obsessed with Duke Basketball.", "I've been to ten countries outside the US!", 'Once I pet 21 dogs in Boston Common.', '', '', 'I\'ve moved 7 times!', 'I hate broccoli.', 'My go-to playlist is over 70 hours of music.'];

var freshmen_reps = {'Career Development Freshmen Rep': ['Tejal Reddy', 'Jianna Liu'], 'Alumni Relations Freshmen Rep': ['Isabella Pedraza Pineros'], 'Campus Representative Freshmen Rep': ['Crista Falk'], 'Department Liaison Freshmen Rep': ['Mulan Jiang'], 'Marketing and Media Freshmen Rep': ['Haijia Wang'], 'SWETube Freshmen Rep': ['Niki Kim', 'Grace Kim'], 'Innovation Freshmen Rep': ['Elaine Wen'], 'School Off-Campus Freshmen Rep': ['Sylvia Waft', 'Brianna Ryan'], 'High School Off-Campus Freshmen Rep': ['Cami Mejia'], 'Festival Freshmen Rep': ['Louise Lima', 'Lili Sun'], 'WiSE Freshmen Rep': ['Shruti Ravikumar'], 'Girl Scouts Freshmen Rep': ['Shelly Ben-David'], 'Girl Scouts Chair': ['Sarah Lohmar'], 'KEYS Freshmen Rep': ['Varsha Sandadi'], 'High School On-Campus Freshmen Rep': ['Veronica Perdomo'], '#HelloWorld Freshmen Rep': ['Portia Gaitskell'], 'Internal Mentorship Freshmen Rep': ['Christina Patterson'], 'Membership Development Freshmen Rep': ['Amanda Hu'], 'External Relations Freshmen Rep': ['Daniela Velez'], 'Web Freshmen Rep': ['Jocelin Su', 'Adrianna Wojtyna'], 'Technology Freshmen Rep': ['Anjalie Kini']};

var departments = [];

function store_info_department() {
    var department = new Object();
    department.name = "EXECUTIVE TEAM";
    department.positions = {"President": ["Haripriya Mehta"],
    "Administrative Officer": ["Ashley Wang"],
    "Financial Officer": ["Priscilla Wong"],
    "VP Off-Campus Outreach": ["Jeana Choi"],
    "VP On-Campus Outreach": ["Nova Xu"],
    "VP Campus Relations": ["Rebekah Costello"],
    "VP Membership": ["Koumani Ntowe"],
    "VP Career Development": ["Julia Pei"],
    "VP Technology": ["Linnea Rylander"]};


    departments.push(department);

    department = new Object();
    department.name = "OFF-CAMPUS OUTREACH";
    department.positions = {"Innovation Chairs": ["Vaibhavi Shah", "Jamie Geng"],
    "School Off-Campus Chairs": ["Naksha Roy", "Phi Xu", "Shana Mathew"],
    "Festival Chairs": ["Stuti Vishwabhan", "Kelly Ho"],
    "High School Off-Campus Chairs": ["Annie Zhang", "Sohini Kar"],
    "SWETube Chairs": ["Rima Rebei", "Aline Vargas"] };
    departments.push(department);

    department = new Object();
    department.name = "ON-CAMPUS OUTREACH";
    department.positions = {"WiSE Chairs": ["Dory Shen", "Rovi Porter"],
        "Girl Scouts Outreach Chairs": ["Fiona Gillespie", "Kaitlyn Hennacy"],
        "KEYS Chairs": ["Camellia Huang", "Kriti Jain"],
        "High School On-Campus Chairs": ["Emma Liu", "Jenny Zhang"],
        "#HelloWorld Chairs": ["Sabrina Liu", "Anna Wong"]};
    departments.push(department);

    department = new Object();
    department.name = "CAMPUS RELATIONS";
    department.positions = {"Campus Representative": ["Shannon Duffy", "Amelia Meles"],
    "Department Liaison": ["Shobhita Sundaram", "Grace Zheng"],
    "Marketing and Media Chairs": ["Elaine Xiao", "Shirley Cao"],
    "SWETube Chairs (Campus)": ["Ariana Adames", "Julia Jia"]};
    departments.push(department);

    department = new Object();
    department.name = "MEMBERSHIP";
    department.positions = {"Internal Mentorship Chairs": ["Laura Huang", "Katharina Gschwind"],
        "Membership Development Chairs": ["Hannah Liu", "Lauren Oh"],
        "External Relations Chairs": ["An Jimenez", "Amanda Deng"],
        "Senior Representatives": ["Jennifer McCleary", "Baula Xu", "Margaret Bertoni",
        "Rebecca Grekin", "Katherine Wang", "Celine Qiu"]};
    departments.push(department);

    department = new Object();
    department.name = "CAREER DEVELOPMENT";
    department.positions = {
        "Career Development Chairs": ["Jocelyn Yao", "Valerie Chen", "Kelly Wu",
        "Lisa Yoo", "Jocelyn Shen", "Joyce Feng", "Danica Dong"],
        "Alumni Relations Chairs": ["Saumya Rawat", "Megan Wei"]
    };
    departments.push(department);

    department = new Object();
    department.name = "TECHNOLOGY";
    department.positions = {"Technology Chairs": ["Francisca Vasconcelos", "Emily Zhang"],
                            "Web Chair": ["Shushu Fang"]};
    departments.push(department);

}
function store_info_members() {
    for (var i=0; i<member_names.length; i++) {
        var current_member = new Object();
        current_member.full_name = member_names[i];
        current_member.first_name = member_names[i].split(" ")[0];
        current_member.last_name = member_names[i].split(" ")[1];
        current_member.photo_url = "../2020_board_pics/" + current_member.first_name + current_member.last_name + ".jpg";
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


             var freshmen_rep_key = position.substring(0, position.indexOf('Chair')) + 'Freshmen Rep'; //check that this gets before Chair
             if (position.indexOf('Chair') == -1) {
                freshmen_rep_key = position + ' Freshmen Rep';
             }
             console.log(freshmen_rep_key);
             if (freshmen_rep_key in freshmen_reps) {
                 const freshmen = freshmen_reps[freshmen_rep_key];
                 console.log(freshmen);
                 for (const freshman of freshmen) {
                     build_person_html(freshmen_rep_key, freshman);
                 }
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
