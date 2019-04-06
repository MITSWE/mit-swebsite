//this file renders all members

var members_info = []; 
var member_names = ["Jocelyn Shen", "Emma Liu", "Annie Zhang", "Megan Wei", "Vaibhavi Shah", "Jenny Zhang", "Lauren Oh", "Aline Vargas", 
"An Jimenez", "Valerie Chen", "Grace Zheng", "Jamie Geng", "Camellia Huang", "Rovi Porter", "Sohini Kar", "Anna Wong", "Amelia Meles",
"Fiona Gillespie", "Emily Zhang", "Julia Jia", "Kelly Ho", "Hannah Liu", "Shirley Cao", "Kriti Jain", 
"Kelly Wu", "Katharina Gschwind", "Shannon Duffy", "Nova Xu", "Ashley Wang", "Haripriya Mehta", "Shushu Fang", "Priscilla Wong",
"Rebekah Costello", "Koumani Ntowe", "Julia Pei", "Kaitlyn Hennacy", "Jennifer McCleary", "Ariana Adames", "Joyce Feng",
"Margaret Bertoni", "Stuti Vishwabhan", "Linnea Rylander", "Rebecca Grekin", "Francisca Vasconcelos", "Rima Rebei", 
"Amanda Deng", "Dory Shen", "Shana Mathew", "Sabrina Liu", "Laura Huang", "Baula Xu", "Katherine Wang", "Celine Qiu", "Jocelyn Yao",
"Phi Xu", "Naksha Roy",
"Jeana Choi", "Shobhita Sundaram", "Elaine Xiao", "Lisa Yoo", "Danica Dong", "Saumya Rawat"];
var member_courses = ["6-3", "6-3", "6-3", "6-3", "20", "6-3", "6-3", "10B", "6-3",
"6-2","18", "20", "20", "1", "6-3", "6-3", "6-3", "6-2","6-3, 18","6-3","6-2","18C",
"2A","6-2/9","10","6-3","6-3","20","6-14","6-2", "6-2", "6-3", "20", "20", "20", "10", "6-2",
"6-14", "6-3, 14-1", "2", "6-3", "6-3", "10", "6-2, 8", "6-3, 9", "6-3, 15", "6-2", "6-2", "6-2", "2", 
"6-3", "6-3", "6-2", "20", "6", "10", "6", "", "", "", "10", ""];
var member_class_year = [2022, 2022, 2022, 2022, 2021, 2021, 2021, 2021, 2021, 2022, 2022, 2022, 2022,
2022, 2022, 2022, 2022, 2022, 2021, 2022, 2022, 2022, 2022, 2021, 2022, 2020, 2020, 2020, 2021, 2020, 
2022, 2020, 2020, 2021, 2020, 2020, 2019, 2022, 2021, 2019, 2020, 2021, 2019, 2021, 2021, 2021, 2020, 2020, 
2021, 2019, 2019, 2019, 2021, 2021, 2022, 2021, 2021, 2022, 2021, 2022, 2022, 2022];

var fun_facts = ["I write novels for fun", "I love Star Wars.", "I like trees.", "I like to eat spicy food.", 
"I've been to over 30 countries!", "I've never broken a bone.", "I got kicked out of preschool.", "Love to Bike!",
"I am a Zumba instructor!", "You may catch me eating fries with a fork...", "I have played piano for 10 years!", 
"I enjoy embroidery in my free time!", "I attended 6th & 7th grade in Taiwan!", "Loves doing the KenKen and Sudoku in MIT weekly newspapers...",
"I've never had Pepsi or Dr. Pepper!", "I love spicy food!", "I love corgis :)", "I love baking cakes :)",
"I can write with both hands!", "I can crochet a mini owl!", "I've watched every episode of Friends.", "I got my Starbucks Gold Card through 1 purchase",
"I like bullet journaling and calligraphy !!!", "I'm on MIT Bhangra!", "Hiking the swiss alps is on my bucket list",
"I can speak 6 languages!", "I trained my fish to follow my finger", "I have a twin brother with a different birthday!",
"I love Hawaii!!", "Unwittingly, I asked Dropbox CEO Drew Houston if he was a current student at MIT. Oops!",
"I bookmarked MIT confessions submission page", "I like to whistle!", "I never watch Marvel movie trailers to avoid any spoilers!",
"I'm left handed.", "I can fold my tongue into the shape of a three-leaf clover!", " I love experimenting with desserts and coming up with new recipes.",
"I play League of Legends.", "I have over 30 snow globes of countries across 4 continents of which I've visited (or family/friends have brought me)!",
"I can say the alphabet quicker backwards than forwards", "I've traveled to four different continents.", "I got to see Pink in concert for free!",
"I can ride a unicycle!", "I went to 12 countries while backpacking in europe for 2.5 months the summer before college!",
"I was born on a Friday the 13th on my mom's birthday.", "I was in a hair commercial in Thailand!", "I like trap music.",
"My favorite type of fried potato is waffle fries.", "I can touch my tongue to my nose!", "I eat apples whole with the core.",
"I love cheesecake!", "I'm really good at pinching people with my toes!", "I'm obsessed with Duke Basketball.", "I've been to ten countries outside the US!",
"I carry tiger balm w/ me in my backpack! hmu if you ever need some", "Once I pet 21 dogs in Boston Common",
"My last name is actually written as and pronounced \"Rai\" in Bengali but \"Roy\" in English.", 
"", "", "", "", "I hate Broccoli", ""];

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
    department.positions = {"Campus Representatives": ["Shannon Duffy", "Amelia Meles"],
    "Department Liaisons": ["Shobhita Sundaram", "Grace Zheng"],
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
        current_member.photo_url = "../2019_board_pics/" + current_member.first_name + "_" + current_member.last_name + ".jpg";
        current_member.course = member_courses[i];
        current_member.class_year = member_class_year[i];
        current_member.fun_facts = fun_facts[i];
        members_info.push(current_member);
    }

}

function find_person(person_name) {
    for (var i in members_info) {
        if (members_info[i].full_name ==person_name) {
            return members_info[i];

        }
    }

        
    return "NOT FOUND";
}

function build_html() {
    for (var i = 0; i<departments.length; i++) {
         var header = document.createElement("h2");
         header.innerHTML = departments[i].name;
         document.getElementById("container").append(header); 

         counter = 0;
         var row_wrapper = document.createElement("div");
         row_wrapper.className = "row";
         for (const [position, people] of Object.entries(departments[i].positions)) {
             for (j in people){
                 var person_object = find_person(people[j]);
                 if (person_object=="NOT FOUND")
                    console.log(people[j]);
                 else {
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
                    member_image.style="width:200px;height:200px;"

                    member_container.appendChild(member_image);
                    //add text
                    var over_lay = document.createElement("div");
                    over_lay.className = "member-overlay";
                    var member_text = document.createElement("div");
                    member_text.className = "member-text";
               
                    var div1 = document.createElement("div");
                    var strong1 = document.createElement("strong");
                    strong1.style = "color: white;"
                    strong1.innerHTML = "Course " + person_object.course + " Class of " + person_object.class_year;
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
                    

                 } //end of else
             }
         } //end of building for each individual
         if (counter!=0)
            document.getElementById("container").append(row_wrapper); 

    }
}


function main() {
    store_info_department();
    store_info_members();
    build_html();
}

main();

