<?php
App::uses('AppModel', 'Model');
App::uses('CakeEmail', 'Network/Email');
/**
 * Comment Model
 *
 * @property Creation $Creation
 */
class Comment extends AppModel {
	
	var $blacklistKeywords = array('2g1c','4chan','a2m','acrotomophilia','adult','amateur','anal','anilingus','anus','are','are idiots','arsehole','aryan','asian babe','ass','asshole','assmunch','auto erotic','autoerotic','babes in toyland','babeland','baby batter','ball gravy','ball sack','ball gag','ball kicking','ball licking','ball sucking','bangbros','bareback','barely legal','barenaked ladies','bastardo','bastinado','bbw','bdsm','beaver cleaver','beaver lips','bestiality','betty dodson','bi curious','bianca beauchamp','big black','big knockers','big tits','bimbos','birdlock','bisexual','bitch','black cock','blonde action','blonde on blonde action','blow j','blow your l','blue waffle','blumpkin','bollocks','bondage','boner','boob','booty call','brown showers','brunette action','bukkake','bulldyke','bullet vibe','bung hole','bunghole','busty','butt','butthole','buttcheeks','camel toe','camgirl','camslut','camwhore','carol queen','carpet muncher','carpetmuncher','chastity belt','chocolate rosebuds','chrissie wunna','circlejerk','cleveland steamer','clit','clitoris','clover clamps','clusterfuck','cocaine','cock','cocks','consensual intercourse','coprolagnia','coprophilia','cornhole','courtney trouble','cream pie','creampie','crossdresser','cuckold','cum','cumming','cunt','cunnilingus','darkie','date rape','daterape','deep throat','deepthroat','dick','dildo','dirty pillows','dirty sanchez','dog style','doggie style','doggiestyle','doggy style','doggystyle','dolcett','domination','dominatrix','dommes','donkey punch','double dong','double penetration','dp action','ducky doolittle','eat my ass','ecchi','ecstasy','ejaculation','electrotorture','erotic','erotism','escort','ethical slut','eunuch','faggot','fantasies','fapserver','fecal','felch','fellatio','feltch','femdom','female desperation','female squirting','fetish','figging','fingering','fisting','foot fetish','footjob','freeones','frotting','fuck','fuck buttons','fudge packer','fudgepacker','futanari','g-spot','gang bang','gay boy','gay dog','gay man','gay men','gay sex','genitals','get my sister','giant cock','ginger lynne','girl on','girl on top','girls gone wild','goatcx','goatse','gokkun','golden shower','goo girl','goodpoop','goodvibes','google is evil','goregasm','gringo','grope','group sex','guro','hairy','hand job','handjob','happy slapping video','hard core','hardcore','hate','hedop','hentai','homoerotic','honkey','hookup','hooker','hot chick','how to kill','how to murder','huge fat','humping','i hate','incest','insertions','interracial','jack off','jackie strano','jacobs ladder piercing','jail bait','jailbait','jenna jameson','jenna jameson','jerk off','jesse jane','jizz','jigaboo','jiggaboo','jiggerboo','john holmes','jordan capri','juggs','kama','kamasutra','kike','kinbaku','kinky','kinkster','knobbing','latina','leather restraint','leather straight jacket','lemon party','lesbian','licked','linda lovelace','lingerie','lolita','lovemaking','lovers','lsd','madison young','make me come','male squirting','masturbate','mature','mdma','meats','menage a trois','miki sawaguchi','milf','missionary position','motherfucker','mound of venus','mr hands','muff diver','muffdiving','murder','naked','nambla','naughty','nawashi','negro','neonazi','new pornographers','nig nog','nigga','nigger','nimphomania','nina hartley','nipples','nonconsent','nsfw images','nude','nympho','nymphomania','octopussy','omorashi','one cup two girls','one guy one jar','orgy','orgasm','paedophile','pamela anderson','panty','panties','paris hilton','pedobear','pedophile','pegging','penis','philip kindred dick','phone sex','piece of shit','piss pig','pissing','pisspig','playboy','pleasure chest','pole smoker','ponyplay','poof','poop chute','poopchute','porn','pre teen','preteen','prince albert piercing','prolapsed','pthc','pubes','pussy','queaf','r@ygold','raghead','raging boner','rape','raping','rapist','rapping women','rectum','redtube','reverse cowgirl','rimjob','rimming','rosy palm','rosy palm and her 5 sisters','rule 34','rusty trombone','s&m','sadie lune','sadism','sasha grey','savage love','scat','schlong','schoolgirl','scissoring','seduced','semen','servitude','serviture','sex','sexo','sexy','shanna katz','shar rednaur','shauna grant','shaved beaver','shaved pussy','shay lauren','shemale','shibari','shit','shota','shrimping','slanteye','sleazy d','slit','slut','smells like teen spirit','smut','snatch','snowballing','sodomize','sodomy','spank','spic','spooge','spread legs','spunky teens','squirt','stickam girl','stileproject','stormfront','strap on','strapon','strappado','strip club','style doggy','submissive','suck','sucks','suicide girls','sultry women','swastika','swinger','tainted love','taste my','tea bagging','teen','tentacle','threesome','throating','tied up','tight white','tit','titties','titty','tongue in a','tosser','towelhead','traci lords','tranny','transexual','tribadism','tub girl','tubgirl','tushy','twat','twink','twinkie','two girls','two girls one cup','undressing','upskirt','urethra play','urophilia','vagina','venus mound','vibrator','violet blue','violet wand','vivid','vorarephilia','voyeur','vulva','wank','wartenberg pinwheel','wartenberg wheel','webcam','wet dream','wetback','white power','women rapping','wrapping men','wrinkled starfish','xx','yaoi','yellow showers','yiffy','zoophilia');
	
	var $blacklistWords = array('.html', '.info', '?', '&', '.de', '.pl', '.cn', '.ru'); 
	
	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'News' => array(
			'className' => 'News',
			'foreignKey' => 'news_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

    public $validate = array(
            'name' => array(
                'mustNotEmpty'=>array(
                    'rule' => 'notBlank',
                    'message'=> 'Please enter your name',
                    'last'=>true),
            ),
            'email'=> array(
                'mustNotEmpty'=>array(
                    'rule' => 'notBlank',
                    'message'=> 'Please enter your email',
                    'last'=>true),
                'mustBeEmail'=> array(
                    'rule' => array('email'),
                    'message' => 'Please enter valid email',
                    'last'=>true),
            ),
            'details'=>array(
                'mustNotEmpty'=>array(
                    'rule' => 'notBlank',
                    'message'=> 'Please enter your comment',
                    'on' => 'create',
                    'last'=>true),
                'mustBeLonger'=>array(
                    'rule' => array('minLength', 6),
                    'message'=> 'Comment must be greater than 5 characters',
                    'on' => 'create',
                    'last'=>true),
            ),
        );
	
	public function newcomments() {
		return $this->find('all', array(
			'conditions' => array(
				'Comment.viewed' => 0), 
			'order' => array('Comment.created' => 'DESC'),)
		);
	}
	
	public function getCreationComments($cid = null) {
		if ($cid) {
			/*
			$cache = Cache::read('getCreationComments' . $cid, 'longterm');
			if (!$cache) {
			*/	
				$this->unbindModel(array('belongsTo' => array('News')));
				$this->virtualFields = array(
					'avatar' => 'SELECT avatar FROM users AS User WHERE User.id = Comment.user_id',
					'author' => "SELECT IF(displayname IS NULL OR displayname = '', IF(username IS NULL OR username = '', 'Anon', username), displayname) FROM users AS User WHERE User.id = Comment.user_id",
					'authdate' => "DATE_FORMAT(Comment.created,'%d %M, %Y')"
				);
				$result = $this->find('threaded', array(
					'conditions' => array(
						'Comment.status' => 1,
						'Comment.news_id' => $cid), 
					'order' => array('Comment.created' => 'DESC'),)
				);

				//Cache::write('getCreationComments' . $cid, $result, 'longterm');
				return $result;
				/*
			} else {
				return $cache;
			}*/
		}
		return array();
	}
	
	public function afterSave($created, $options = Array()) {
        if ($created) {
            
            $data = $this->data['Comment'];
            $this->id = $this->getLastInsertID();
            $points =  0;
            if (!empty($data) && is_array($data)) {
                // Get links in the content
                $links = preg_match_all("#(^|[\n ])(?:(?:http|ftp|irc)s?:\/\/|www.)(?:[-A-Za-z0-9]+\.)+[A-Za-z]{2,4}(?:[-a-zA-Z0-9._\/&=+%?;\#]+)#is", $data['details'], $matches);
                $links = $matches[0];
                
                $totalLinks = count($links);
                $length = strlen($data['details']);
        
                // How many links are in the body
                // +2 if less than 2, -1 per link if over 2
                if ($totalLinks > 2) {
                    $points = $points - $totalLinks;
                } else {
                    $points = $points + 2;
                }
                
                // How long is the body
                // +2 if more then 20 chars and no links, -1 if less then 20
                if ($length >= 20 && $totalLinks <= 0) {
                    $points = $points + 2;
                } else if ($length >= 20 && $totalLinks == 1) {
                    ++$points;
                } else if ($length < 20) {
                    --$points;
                }
                
                // Number of previous comments from email
                // +1 per approved, -1 per spam
                $comments = $this->find('all', array(
                    'fields' => array('Comment.id', 'Comment.status'),
                    'conditions' => array('Comment.email' => $data['email']),
                    'recursive' => -1,
                    'contain' => false
                ));
                
                if (!empty($comments)) {
                    foreach ($comments as $comment) {
                        if ($comment['Comment']['status'] == 0) {
                            --$points;
                        }
                        
                        if ($comment['Comment']['status'] == 1) {
                            ++$points;
                        }
                    }
                }
                
                // Keyword search
                // -1 per blacklisted keyword
                foreach ($this->blacklistKeywords as $keyword) {
                    if (stripos($data['details'], $keyword) !== false) {
                        --$points;
                    }
                }
                
                // URLs that have certain words or characters in them
                // -1 per blacklisted word
                // URL length
                // -1 if more then 30 chars
                foreach ($links as $link) {
                    foreach ($this->blacklistWords as $word) {
                        if (stripos($link, $word) !== false) {
                            --$points;
                        }
                    }
                    
                    foreach ($this->blacklistKeywords as $keyword) {
                        if (stripos($link, $keyword) !== false) {
                            --$points;
                        }
                    }
                    
                    if (strlen($link) >= 30) {
                        --$points;
                    }
                }    
                
                // Body starts with...
                // -10 points
                $firstWord = substr($data['details'], 0, stripos($data['details'], ' '));
                $firstDisallow = array_merge($this->blacklistKeywords, array('crap', 'wtf', 'sorry'));
                
                if (in_array(strtolower($firstWord), $firstDisallow)) {
                    $points = $points - 10;
                } 
                
                // Author name has http:// in it
                // -2 points
                if (stripos($data['email'], 'http://') !== false) {
                    $points = $points - 2;
                }
                
                // Body used in previous comment
                // -1 per exact comment
                $previousComments = $this->find('count', array(
                    'conditions' => array('Comment.details' => $data['details']),
                    'recursive' => -1,
                    'contain' => false
                ));
                
                if ($previousComments > 0) {
                    $points = $points - $previousComments;
                }
                
                // Random character match
                // -1 point per 5 consecutive consonants
                $consonants = preg_match_all('/[^aAeEiIoOuU\s]{5,}+/i', $data['details'], $matches);
                $totalConsonants = count($matches[0]);
                
                if ($totalConsonants > 0) {
                    $points = $points - $totalConsonants;
                }
                
                // Finalize and save
                if ($points >= 1) {
                    $status = 1;
                } else if ($points == 0) {
                    $status = 0;
                } else if ($points <= Configure::read('MySite.comment_spam_limit')) {
                	$status = 9;
                } else {
                    $status = 0;
                }
                
                
                if (Configure::read('MySite.delspam_comments') && $status == 9) {
                	$data['status'] = $status;
                	$data['points'] = $points;
                	$this->log($data, 'critical', 'activity');
                    $this->delete($this->id, false);
                    $this->log(__('Comment deleted by system due to inappropriate content.') . ' ' . $this->id, 'important', 'activity');
                } else {
                    $update = array();
                    $update['id'] = $this->id;
                    $update['status'] = $status;
                    $update['points'] = $points;
                    $update['keychain'] = md5($data['id'] . $data['email']);
                    $this->save($update);
                    if (Configure::read('MySite.notify_comments')) {
                    	$this->sendUserMail($data, $update);
                    }
                }        
            }
            
            return $points;
        }
    } 
    
    public function isApproved($key = null) {
    	if ($key){
    		$result = $this->findByKeychain($key);
    		if ($result && ($key === md5($result['Comment']['id'] . $result['Comment']['email']))) {
    			$this->id = $result['Comment']['id'];
    			$this->saveField('status', 1);
    			return true;
    		}
    	}
    	return false;
    }
  
    private function sendUserMail($data, $stats) {

        ClassRegistry::init('News')->recursive = -1;
        $post = ClassRegistry::init('News')->read(null, $data['news_id']);

        if ($data && $post) {

            $email = new CakeEmail('default');
            $email->config(array('from' => array($data['email'] => $data['name'])));
            $email->replyTo(Configure::read('MySite.send_email'));
            $email->returnPath(Configure::read('MySite.send_email'));
            $email->addHeaders(array('Organization' => Configure::read('MySite.site_name'), 'X-Priority' => 3));
            $email->helpers(array('Html'));
            $email->subject(__('Comment for Approval :: ' . $post['News']['title']));
            $email->template('newcomment');
            $email->emailFormat('text');
            $email->to(Configure::read('MySite.send_email'));
            $theme = Configure::read('MySite.theme');
            if (!empty($theme)) {
                $email->theme($theme);
            }
            $email->viewVars(array(
                'sitename' => Configure::read('MySite.site_name'),
                'post' => $post,
                'comment' => $data,
                'stats' => $stats,
            ));
            
            try{
                $result = $email->send();
            } catch (Exception $ex) {
                $result = __('Could not send email to user');
            }
            $this->log($result, 'important', 'activity');
        }

    }

}
