<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210423143700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        
        //users
        $this->addSql('INSERT INTO user (email, roles, name, password, active) VALUES
            ("mcmorris@test.com", "[]", "Mark McMorris", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("chorlton@test.com", "[]", "Tyler Chorlton", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("ostreng@test.com", "[]", "Alek Ostreng", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("rinnekangas@test.com", "[]", "Rene Rinnekangas", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("kleveland@test.com", "[]", "Marcus Kleveland", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("mattson@test.com", "[]", "Niklas Mattson", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("bergrem@test.com", "[]", "Torgeir Bergrem", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1"),
            ("kadono@test.com", "[]", "Yuki Kadono", "$2y$10$dGKZr7/7T5uQNZIHFk91fewSRanWB065eWb7J.B0zd.na9TFq0/kS", "1")
            ');
        
        // categories
        $this->addSql('INSERT INTO category (name) VALUES
            ("grab"),
            ("rotation"),
            ("flip"),
            ("rotation désaxée"),
            ("slide"),
            ("one foot"),
            ("old school")
        ');
        // avatar
        $this->addSql('INSERT INTO avatar (user_id, url) VALUES
            ("1","https://img.redbull.com/images/c_crop,x_898,y_0,h_2133,w_2133/c_fill,w_90,h_90/q_auto,f_auto/redbullcom/2020/11/13/z2u7twuc3ujoe0gee7a9/mark-mcmorris-saas-fee-2020"),
            ("2","https://talenthouse-res.cloudinary.com/image/upload/c_limit,f_auto,fl_progressive,h_1280,w_1280/v1396428191/user-283094/profile/hja1ykxc2rulekgytqnc.jpg"),
            ("3","https://previews.123rf.com/images/djvstock/djvstock1802/djvstock180214029/96445159-avatar-man-with-snowboard-equipment-icon-over-white-background-colorful-design-vector-illustration.jpg"),
            ("4","https://cdn2.iconfinder.com/data/icons/activity-5/50/1F3C2-snowboard-512.png"),
            ("5","https://media.istockphoto.com/vectors/warm-dressed-man-snowboarder-skier-icon-avatar-and-person-vector-id513235358?k=6&m=513235358&s=170667a&w=0&h=aIT6EwvbiRfeHlFv3dYexUp-70B4FRES6nHZrLjJFNk="),
            ("6","https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcThVPtR39INm3khl223S9SNAF4nWOtdjw5-mg&usqp=CAU"),
            ("7","https://www.horsefeathers.eu/data/tmp/17/4/60454_17.jpg?1605542036"),
            ("8","https://avatarfiles.alphacoders.com/254/thumb-254984.jpg")
        ');
        // trick
        $this->addSql('INSERT INTO trick (name, description, created_at, updated_at, slug, category_id, author_id) VALUES
            (
                "Elbow Carve",
                "Difficulty: Easy. The elbow carve is a direct descendent of the Euro carve, a stylised heel or toe side carve popularised by European hard booter Serge Vitelli back in the late 1980’s. Today, style meisters like Tyler Chorlton are taking things to the next level of creativity and the variations seem to be endless. All you need to get your elbow carve going is a relatively smooth stretch of slope, edge control and a decent set of core muscles – what are you waiting for?",
                "2020-01-20 15:30:00", 
                "2020-02-20 15:38:00", 
                "elbow-carve", 
                "1", 
                "2"
            ),
            (
                "Andrecht",
                "Difficulty: Easy-ish. Handplants are a must-have in any decent riders trickbag: not only do they look and feel great, they’re pretty easy too – at least, a lot easier strapped onto a snowboard than on a skateboard. Norwegian style boss Alek Ostreng can do them on both, and it shows: this classic Andrechthandplant is as smooth and stalled as it gets.",
                "2020-02-20 15:30:00", 
                "2020-06-20 15:38:00", 
                "andrecht", 
                "2", 
                "3"
            ),
            (
                "Frontside Boardslide backside grab",
                "Difficulty: Tricky. Rene Rinnekangas is a young Finnish rider you’ve probably never heard of, but will be seeing a lot more of. And this trick is something you’ve probably never thought of, but now you’ve seen it you’ll wish you could do it too. It’s probably not even that hard, if you do it on a long, widefunbox in the park. Unlike Rene, who’s doing it on a legit rail, with one of the sketchiest in-runs ever…",
                "2020-03-20 15:30:00", 
                "2020-07-20 15:38:00", 
                "frontside-boardslide-backside-grab", 
                "1", 
                "4"
            ),
            (
                "Backside 180 Rewind",
                "Difficulty: Easy when you’re Marcus. The Rewind was the freshest move of 2017, and it’s pretty much the hardest trick variation you can do – basically it’s when you almost complete a full rotation – 360, 540, 720, whatever – then at the last minute reverse spin direction and ‘rewind’ 180 degrees. In this case, Marcus is sending a huge, inverted backside 3, but stalls, pokes and brings it back to 180. This is the easiest rotation done in the hardest possible way.",
                "2020-04-20 15:30:00", 
                "2020-08-20 15:38:00", 
                "backside-180-rewind", 
                "4", 
                "5"
            ),
            (
                "Backside Lipslide to frontflip",
                "Difficulty: Medium-hard. Niklas is a true rider’s rider – he might not appear on many podiums, but he’s as talented and enjoyable to watch as anyone in the world right now. So while this Swedish ripper is perfectly capable of blowing your mind with a backside 1620, it’s this silly, but incredible backside lipperfrontflip out that we’d like to draw your attention to.",
                "2020-05-20 15:30:00", 
                "2020-06-20 15:38:00", 
                "backside-lipslide-to-frontflip", 
                "4", 
                "6"
            ),
            (
                "Switch Backside 540 late Method",
                "Difficulty: Harder than it looks. Norwegian Snow God Torgeir Bergrem knows that triple corks might win contests, but style and creativity wins respect: this butter-smooth switch backside 540 to late Method might just be the most stylish trick ever performed on a snowboard.",
                "2020-06-20 15:30:00", 
                "2020-09-20 15:38:00", 
                "switch-backside-540-late-method", 
                "5", 
                "7"
            ),
            (
                "Backside double cork 1080 Rewind",
                "Difficulty: Crazy difficult. Backside 3 Rewinds are soooo October ’17. Swedish creative genius Niklas Mattson brought this tricky rotation variation to the next level in April ’18 when he rewound a double cork 10. Better learn this one quick, it’s already getting played out…",
                "2020-08-20 15:30:00", 
                "2020-11-20 15:38:00", 
                "backside-double-cork-1080-rewind", 
                "6", 
                "8"
            ),
            (
                "Front Board dub 12",
                "Difficulty: Ridiculous. Here’s one for the In-Your-Dreams wishlist – Mark McMorris has totally put last year’s heavy injury season to bed with this crazy NBD – we’re not quite sure what to call it, but it’s basically a backside 1170° double cork out of a front board.",
                "2020-09-20 15:30:00", 
                "2020-09-20 15:30:00", 
                "front-board-dub", 
                "7", 
                "1"
            ),
            (
                "Backside Double Cork 1170 Off a Rail",
                "After deeming the backside double cork 1170 off of a rail possible, McMorris worked with legendary terrain park builder Charles Beckinsale to make the right feature for the trick a reality at Corvatsch 3303 in Switzerland. The result was a hulking cannon rail, designed to give the height and distance necessary to muscle around every degree of the BS double cork 1170. Watch him nail the historic trick in the video above.",
                "2020-10-20 15:30:00", 
                "2020-10-20 15:30:00", 
                "backsidedouble-cork-1170-off-a-rail", 
                "2", 
                "1"
            ),
            (
                "Backside Quad 1980°",
                "Yuki was always going to become a member of the Quad Club, and trust the Japanese freestyle ninja to out-do everyone else by adding an extra 180° to the mix, making this Backside Quad 1980° the biggest rotation ever performed on a snowboard.",
                "2020-11-20 15:30:00", 
                "2020-11-20 15:30:00", 
                "backside-quad", 
                "3", 
                "8"
            )
        ');
        // media
        $this->addSql('INSERT INTO media (url, trick_id, type) VALUES 
        ("https://sequence-magazine.com/contents/wp-content/uploads/2018/02/elbowsedges-2018-italy_52.jpg", "1", "img"),
        ("https://www.youtube.com/embed/mSDHv8N3wDg", "1", "video"),
        ("https://i.ytimg.com/vi/2iS21nuz03Y/sddefault.jpg", "2", "img"),
        ("https://bettyrides.files.wordpress.com/2008/04/celia.jpeg", "2", "img"),
        ("https://www.youtube.com/embed/348hV_b2sL4", "2", "video"),
        ("https://i.redd.it/u6dlfoqshqo01.jpg", "3", "img"),
        ("https://miro.medium.com/max/2730/1*kPKILFHyiUYP-j7AHjM_Kw.jpeg", "3", "img"),
        ("https://www.youtube.com/embed/t2yLBRU2Uz8", "3", "video"),
        ("https://pushdalimit.com/push/wp-content/uploads/2018/11/pushBS180Thumb-min.jpg", "4", "img"),
        ("https://www.youtube.com/embed/z4x42HZu8Sg", "4", "video"),
        ("https://i.ytimg.com/vi/pfkiK_RBsNc/maxresdefault.jpg", "5", "img"),
        ("https://www.edshreds.com/wp-content/uploads/2020/07/rail-sliding.png", "5", "img"),
        ("https://www.youtube.com/embed/pfkiK_RBsNc", "5", "video"),
        ("https://i.ytimg.com/vi/P5ZI-d-eHsI/maxresdefault.jpg", "6", "img"),
        ("https://www.youtube.com/embed/P5ZI-d-eHsI", "6", "video"),
        ("https://i.ytimg.com/vi/j4NfAsszIOk/maxresdefault.jpg", "7", "img"),
        ("https://img.redbull.com/images/c_limit,w_1500,h_1000,f_auto,q_auto/redbullcom/2014/05/20/1331652670742_2/katie-ormerod-first-women-s-double-cork-1080", "7", "img"),
        ("https://www.youtube.com/embed/_3C02T-4Uug", "7", "video"),
        ("https://i.ytimg.com/vi/ThD2BOASl2k/maxresdefault.jpg", "8", "img"),
        ("https://www.youtube.com/embed/ThD2BOASl2k", "8", "video"),
        ("https://img.redbull.com/images/c_crop,x_0,y_123,h_1257,w_2514/c_fill,w_1920,h_960/q_auto,f_auto/redbullcom/2018/05/06/ea3dcb95-8292-4a3d-abae-7f99c9dc88eb/mark-mcmorris-first-bs-double-cork-1170-off-a-rail", "9", "img"),
        ("https://www.telegraph.co.uk/content/dam/Travel/ski/billy_morgan_quad_cork_1800.jpg?impolicy=logo-overlay", "10", "img"),
        ("https://www.youtube.com/embed/EFBct2TWKdk", "10", "video")
        ');

        $comments_content = [
            "Super !",
            "Impressionnant !",
            "Yeah, Awesome !",
            "Un jour pourtant, une petite ligne de Bolo Bolo du nom de Lorem Ipsum décida de s'aventurer dans la vaste Grammaire.",
            "Le grand Oxymore voulut l'en dissuader, le prevenant que là-bas cela fourmillait de vils Virgulos, de sauvages ",
            "Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique.",
            "Pointdexclamators et de sournois Semicolons qui l'attendraient pour sûr au prochain paragraphe",
            "Un petit ruisseau, du nom de Larousse, coule en leur lieu et les approvisionne en règlalades nécessaires en tout genre",
            "Un pays paradisiagmatique, dans lequel des pans entiers de phrases prémâchées vous volent litéralement tout cuit dans la bouche.",
            "Pas même la toute puissante Ponctuation ne régit les Bolos Bolos - une vie on ne peut moins orthodoxographique",
        ];

        for ($i=0; $i < 300; $i++) { 
            $this->addSql('INSERT INTO comment (trick_id, content, created_at, author_id) VALUES
                ("' . rand(1,10) . '",
                "'. $comments_content[array_rand($comments_content)] . '",
                "2020-' . rand(1,12) . '-' . rand(1,28) . ' 15-00-00",
                "'.rand(1,8).'")
            ');
        }

        // comment
        
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM user');
        $this->addSql('DELETE FROM category');
        $this->addSql('DELETE FROM avatar');
        $this->addSql('DELETE FROM trick');
        $this->addSql('DELETE FROM media');
        $this->addSql('DELETE FROM comment');
    }
}
