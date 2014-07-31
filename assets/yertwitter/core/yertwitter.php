<?php
    
    // TWITTER ( Version 4 ) {
        
        /* usage:
        
            $twitter = new YerTwitter( $param );
            $twitter->echoTweets();
            // $twitter->getTweets();
        */
        
        class YerTwitter {

            private $param_default = array(
                
                'twitteroauth_path' => false,
                'consumer_key' => false,
                'consumer_secret' => false,
                'access_token' => false,
                'access_token_secret' => false,
                
                'wordpress_transient_name' => 'yertwitter_wordpress_transient_name_1',
                
                'screen_name' => false,
                'tweets_count' => 10,
                'exclude_replies' => false,
                'include_rts' => true,
                
                'links_as_hyperlinks' => true,
                'hashtags_as_hyperlinks' => true,
                'twitterusers_as_hyperlinks' => true,
                
                'html_list' => '<ul class="twitterlist">{{content}}</ul>',
                'html_list_item' => '<li class="twitterlist_item">{{content}}</li>',
                'html_content' => '{{time}} {{message}} {{link}}',
                'html_time' => '<span class="twitterlist_date"><abbr title="{{time_stamp}}">{{time}}</abbr></span>',
                'html_link' => '<a href="{{url}}" target="_blank">{{text}}</a>',
                
                'locale_code' => 'en-US',
                
                'link_text' => array(
                    'en-US' => 'more tweets',
                    'de-DE' => 'mehr Tweets',
                ),
                
                'time_format' => array(
                    'en-US' => '%m/%d/%Y',
                    'de-DE' => '%d.%m.%Y',
                ),
                'time_human_range' => 604800, // false or second ( 60 * 60 * 24 * 7 )
                'time_human_text' => array(
                    'en-US' => array(
                        'x seconds ago' => 'some seconds ago',
                        '1 minute ago' => 'about 1 minute ago',
                        'x minutes ago' => '{{x}} minutes ago',
                        '1 hour ago' => 'about 1 hour ago',
                        'x hours ago' => 'about {{x}} hours ago',
                        'yesterday' => 'yesterday',
                        'x days ago' => '{{x}} days ago',
                    ),
                    'de-DE' => array(
                        'x seconds ago' => 'vor wenigen Sekunden',
                        '1 minute ago' => 'vor einer Minute',
                        'x minutes ago' => 'vor {{x}} Minuten',
                        '1 hour ago' => 'vor einer Stunde',
                        'x hours ago' => 'vor {{x}} Stunden',
                        'yesterday' => 'yesterday',
                        'x days ago' => 'vor {{x}} Tagen',
                    )
                ),
            );
            private $param = array();
            private $cacheTime = 600;
            private $twitterData;

            public function __construct( $p ) {

                $this->getParameter( $p );
                
                $this->getTwitterData();
            }
            
            private function getParameter( $p ) {
                
                $this->param = array_replace_recursive( $this->param_default, $p );
            }
            
            private function getTwitterData() {
                
                // get the Twitter Data {
                    
                    if ( false === ( $this->twitterData = get_transient( $this->param['wordpress_transient_name'] ) ) ) {

                        // require the twitter auth class
                        require_once ( $this->param['twitteroauth_path'] );

                        $twitterConnection = new TwitterOAuth(
                            $this->param['consumer_key'],	// Consumer Key
                            $this->param['consumer_secret'],   	// Consumer secret
                            $this->param['access_token'],       // Access token
                            $this->param['access_token_secret']    	// Access token secret
                        );
                        
                        // Parameter 'statuses/user_timeline' https://dev.twitter.com/docs/api/1.1/get/statuses/user_timeline
                        $this->twitterData = $twitterConnection->get(
                            'statuses/user_timeline',
                            array(
                                'screen_name'     => $this->param['screen_name'],
                                'count'           => $this->param['tweets_count'],
                                'exclude_replies' => $this->param['exclude_replies'],
                                'include_rts'     => $this->param['include_rts'],
                            )
                        );

                        if ( $twitterConnection->http_code != 200 ) {

                            $this->twitterData = get_transient( $this->param['wordpress_transient_name'] );
                        }

                        // Save our new transient.
                        set_transient( $this->param['wordpress_transient_name'], $this->twitterData, $this->cacheTime );
                    }
                    
                // }

            }

            private function relPath( $path ) {

                $array = array (
                    'http://' . $_SERVER["HTTP_HOST"] . '/'  => ""
                );

                return strtr( $path, $array );
            }

            public function getTweets() {

                $return = false;

                if( !empty( $this->twitterData ) || !isset( $this->twitterData['error'] ) ) {

                    $i = 0;
                    //$encode_utf8 = true;
                    $update = true;
                    $returnList = false;
                    
                    if ( count( $this->twitterData ) > 0 ) {
                            
                        foreach ( $this->twitterData as $item ) {
                            
                            $return_item = $this->param['html_content'];
                            $msg = $item->text;
                            //$permalink = 'https://twitter.com/#!/'. $this->param['screen_name'] .'/status/'. $item->id_str;
                            //$msg = utf8_encode( $msg );
                            //$msg = $this->encode_tweet($msg);
                            //$link = $permalink;

                            // RETWEET {

                                if ( $this->param['include_rts'] && isset( $item->retweeted_status ) ) {
                                    
                                    $msg = 'Retweet @' . $item->retweeted_status->user->screen_name . ': ';
                                    $msg .= $item->retweeted_status->text;
                                }

                            // }
                            
                            // MESSAGE {

                                if ( $this->param['links_as_hyperlinks'] ) {

                                    $msg = $this->links_as_hyperlinks( $msg );
                                }
                                
                                if ( $this->param['hashtags_as_hyperlinks'] ) {

                                    $msg = $this->hashtags_as_hyperlinks( $msg );
                                }

                                if ( $this->param['twitterusers_as_hyperlinks'] ) {

                                    $msg = $this->twitterusers_as_hyperlinks( $msg );
                                }
                                
                                $return_item = str_replace( '{{message}}', $msg, $return_item );
                                
                            // }
                            
                            // TIME {

                                if ( strpos( $this->param['html_content'], '{{time}}' ) !== false ) {
                                    
                                    $html_time = $this->param['html_time'];
                                    $created_at_timestamp = strtotime( $item->created_at );
                                    $time_diff = abs( time() - $created_at_timestamp );

                                    if ( $this->param['locale_code'] ) {
                                       
                                       setlocale( LC_ALL, $this->param['locale_code'] ); 
                                    }
                                    
                                    if ( $time_diff < $this->param['time_human_range'] ) {
                                        
                                        if ( $time_diff < 60 ) {
                                        
                                            $time_str = str_replace( '{{x}}', $time_diff , $this->param['time_human_text'][ $this->param['locale_code'] ]['x seconds ago'] );
                                        } 
                                        elseif ( $time_diff < 120 ) {
                                        
                                            $time_str = $this->param['time_human_text'][ $this->param['locale_code'] ]['1 minute ago'];
                                        }
                                        elseif ( $time_diff < ( 60 * 60 ) ) {
                                        
                                            $time_str = str_replace( '{{x}}', ( floor( $time_diff / 60 ) ) , $this->param['time_human_text'][ $this->param['locale_code'] ]['x minutes ago'] );
                                        }
                                        elseif ( $time_diff < ( 120 * 60 ) ) {
                                        
                                            $time_str = $this->param['time_human_text'][ $this->param['locale_code'] ]['1 hour ago'];
                                        }
                                        elseif ( $time_diff < ( 24 * 60 * 60 ) ) {
                                            
                                            $time_str = str_replace( '{{x}}', ( floor( ( $time_diff / 3600 ) ) ) , $this->param['time_human_text'][ $this->param['locale_code'] ]['x hours ago'] );
                                        }
                                        elseif ( $time_diff < ( 48 * 60 * 60 ) ) {
                                        
                                            $time_str = $this->param['time_human_text'][ $this->param['locale_code'] ]['yesterday'];
                                        }
                                        else {
                                            
                                            $time_str = str_replace( '{{x}}', ( floor( ( $time_diff / 86400 ) ) ) , $this->param['time_human_text'][ $this->param['locale_code'] ]['x days ago'] );
                                        }
                                    }
                                    else {
                                        
                                        $time_str = strftime( $this->param['time_format'][ $this->param['locale_code'] ], $created_at_timestamp );
                                    }
                                    
                                    $html_time = str_replace( '{{time_stamp}}', date(__('Y/m/d H:i:s'), $created_at_timestamp ) , $html_time );
                                    $html_time = str_replace( '{{time}}', $time_str , $html_time );
                                    $html_time = sprintf( __('%s', 'twitter-for-wordpress'), $html_time );
                                    
                                    $return_item = str_replace( '{{time}}', $html_time, $return_item );
                                }
                                
                            // }
                            
                            // LINK {

                                $accountlink = 'https://twitter.com/'. $this->param['screen_name'] .'/';
                                $html_link = $this->param['html_link'];
                                $html_link = str_replace( '{{url}}', $accountlink, $html_link );
                                
                                $html_link = str_replace( '{{text}}', $this->param['link_text'][ $this->param['locale_code'] ], $html_link );
                                $return_item = str_replace( '{{link}}', $html_link, $return_item );

                            // }
                            
                            $returnList .= str_replace( '{{content}}', $return_item, $this->param['html_list_item'] );
                            
                            $i++;

                            if ( $i >= $this->param['tweets_count'] )  break;
                        }

                        $return = str_replace( '{{content}}', $returnList, $this->param['html_list'] );
                    }
                }

                return $return;
            }

            public function echoTweets () {
                
                echo $this->getTweets();
            }

            private function links_as_hyperlinks( $text ) {

                $text = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&#038;%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\" target=\"_blank\">$1</a>", $text);
                $text = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&#038;%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\" target=\"_blank\">$1</a>", $text);

                // match name@address
                $text = preg_replace("/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i","<a href=\"mailto://$1\" class=\"twitter-link\">$1</a>", $text);
                return $text;
            }
            
            private function hashtags_as_hyperlinks( $text ) {

                //mach #trendingtopics. Props to Michael Voigt
                $text = preg_replace('/([\.|\,|\:|\|\|\>|\{|\(]?)#{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/search?q=%23$2&src=hash\" class=\"twitter-link\" target=\"_blank\">#$2</a>$3 ", $text);
                return $text;
            }

            /**
            * Find twitter usernames and link to them
            */
            private function twitterusers_as_hyperlinks( $text ) {

                $text = preg_replace('/([\.|\,|\:|\|\|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\" target=\"_blank\">@$2</a>$3 ", $text);
                return $text;
            }

            /**
            * Encode single quotes in your tweets
            */
            private function encode_tweet( $text ) {

                $text = mb_convert_encoding( $text, "HTML-ENTITIES", "UTF-8");
                return $text;
            }
        }

    // }
    
?>