<?php

// https://www.eventbrite.de/api/v3/destination/events/?event_ids=1100253942159,1718116154669,1638948271399,1965571211405,1967713906263,1921352409779,1918056040249,1963937640350,1931438457419,1965584366753,1917259287139,1756495578559,1394484694259,1394549096889,1394585044409,1735765644769,1306691562639,1647753076809,1969349072088,1848747957909&page_size=20&expand=event_sales_status,image,primary_venue,saves,ticket_availability,primary_organizer,public_collections
/*
{
    "pagination": {
        "object_count": 20,
        "continuation": null,
        "page_count": 1,
        "page_size": 20,
        "has_more_items": false,
        "page_number": 1
    },
    "events": [
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#000032",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F909282113%2F421624421969%2F1%2Foriginal.20241201-114903?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C0%2C2160%2C1080&s=775e96c3505657b68c1414bd722043f3",
                "id": "909282113",
                "crop_mask": {
                    "width": 2160,
                    "height": 1080,
                    "top_left": {
                        "y": 0,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F909282113%2F421624421969%2F1%2Foriginal.20241201-114903?auto=format%2Ccompress&q=75&sharp=10&s=58ea9956d565894afa6e3ea445566747",
                    "width": 2160,
                    "height": 1080
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2024-12-01T00:00:00",
                    "utc": "2024-11-30T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-21T22:00:00",
                    "utc": "2025-11-21T21:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1100253942159",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "29824533491",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1100253942159",
            "hide_end_date": true,
            "start_date": "2025-11-22",
            "end_time": "22:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "17.17",
                    "value": 1717,
                    "display": "17.17 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "15.03",
                    "value": 1503,
                    "display": "15.03 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-22",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/2002",
                    "display_name": "Science"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/102",
                    "display_name": "Science & Technology",
                    "localized": {
                        "display_name": "Science & Technology"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/2",
                    "display_name": "Seminar or Talk"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/comedy",
                    "display_name": "comedy",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/show",
                    "display_name": "show",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/hypnose",
                    "display_name": "hypnose",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/mentalist",
                    "display_name": "mentalist",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/bühne",
                    "display_name": "bühne",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/chiemgau",
                    "display_name": "chiemgau",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/gedankenlesen",
                    "display_name": "gedankenlesen",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/übersee",
                    "display_name": "übersee",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/michael_kamml",
                    "display_name": "michael_kamml",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/paraminds_event",
                    "display_name": "paraminds_event",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1100253942159",
            "start_time": "19:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Wirtshaus D'Feldwies",
                "venue_profile_id": null,
                "address": {
                    "city": "Übersee",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.4851003",
                    "localized_address_display": "Greimelstr. 30, 83236 Übersee",
                    "postal_code": "83236",
                    "address_1": "Greimelstr. 30",
                    "address_2": "",
                    "latitude": "47.836456",
                    "localized_multi_line_address_display": [
                        "Greimelstr. 30",
                        "83236 Übersee"
                    ],
                    "localized_area_display": "Übersee"
                },
                "venue_profile_url": "",
                "id": "202409279"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Michael Kamml",
                "profile_type": "organizer",
                "num_followers": 17,
                "url": "https://www.eventbrite.de/o/michael-kamml-29824533491",
                "twitter": null,
                "summary": "Michael Kamml ist der Mentalist aus dem Chiemgau. Seine Liveshows sind interaktiv, lustig aber auch tiefgründig, inspirierend und geheimnisvoll. Er agiert mit unseren Gedanken, unseren Emotionen und u...",
                "num_saves": null,
                "image_id": "483331879",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "29824533491",
                "website_url": "https://michaelkamml.com",
                "num_following": null
            },
            "series_id": null,
            "image_id": "909282113",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "202409279",
            "checkout_flow": "widget",
            "name": "Michael Kamml - Paraminds",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/michael-kamml-paraminds-tickets-1100253942159",
            "hide_start_date": false,
            "summary": "Die brandneue Bühnenshow des Chiemgauer Mentalisten",
            "is_online_event": false,
            "eid": "ercgdczz",
            "published": "2024-12-12T09:40:28Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#266841",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1171129390%2F261311624106%2F1%2Foriginal.20251117-102529?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C8%2C1696%2C848&s=c3fc73a44229d4e309ad350c042bd8c8",
                "id": "1171129390",
                "crop_mask": {
                    "width": 1696,
                    "height": 848,
                    "top_left": {
                        "y": 8,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1171129390%2F261311624106%2F1%2Foriginal.20251117-102529?auto=format%2Ccompress&q=75&sharp=10&s=426556095e731da4e29fd1140fe13dc7",
                    "width": 1696,
                    "height": 864
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-07-16T00:00:00",
                    "utc": "2025-07-15T22:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-22T23:30:00",
                    "utc": "2025-11-22T22:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1718116154669",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "17486957800",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1718116154669",
            "hide_end_date": true,
            "start_date": "2025-11-22",
            "end_time": "02:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "10.00",
                    "value": 1000,
                    "display": "10.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "10.00",
                    "value": 1000,
                    "display": "10.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-23",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/16008",
                    "display_name": "Fall events"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/116",
                    "display_name": "Seasonal & Holiday",
                    "localized": {
                        "display_name": "Seasonal & Holiday"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/18",
                    "display_name": "Camp, Trip, or Retreat"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/relaxation",
                    "display_name": "relaxation",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/vacation",
                    "display_name": "vacation",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/break",
                    "display_name": "break",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/autumn",
                    "display_name": "autumn",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/leaves",
                    "display_name": "leaves",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1718116154669",
            "start_time": "20:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Private Landbrauerei Schönram GmbH & Co. KG",
                "venue_profile_id": null,
                "address": {
                    "city": "Petting",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.8496895",
                    "localized_address_display": "Wasserbrenner 33, 83367 Petting",
                    "postal_code": "83367",
                    "address_1": "Wasserbrenner 33",
                    "address_2": "",
                    "latitude": "47.8858016",
                    "localized_multi_line_address_display": [
                        "Wasserbrenner 33",
                        "83367 Petting"
                    ],
                    "localized_area_display": "Petting"
                },
                "venue_profile_url": "",
                "id": "287904193"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "WoWeBa Live GmbH",
                "profile_type": "organizer",
                "num_followers": 24,
                "url": "https://www.eventbrite.de/o/woweba-live-gmbh-17486957800",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": "alohapromotion",
                "num_collections": null,
                "id": "17486957800",
                "website_url": "http://www.aloha-promotion.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1171129390",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "287904193",
            "checkout_flow": "widget",
            "name": "Fallbreak",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/fallbreak-tickets-1718116154669",
            "hide_start_date": false,
            "summary": "Get Ready for Fallbreak!  inkl. Shuttlebus Traunstein - Schönram - Freilassing Fahrplan im Titelbild!",
            "is_online_event": false,
            "eid": "ewpxwygh",
            "published": "2025-09-19T06:49:02Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": null,
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1107492803%2F261311624106%2F1%2Foriginal.20250828-110105?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C2%2C966%2C483&s=46a0b9bcec9785b220b6fa3ff0fd1d54",
                "id": "1107492803",
                "crop_mask": {
                    "width": 966,
                    "height": 483,
                    "top_left": {
                        "y": 2,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1107492803%2F261311624106%2F1%2Foriginal.20250828-110105?auto=format%2Ccompress&q=75&sharp=10&s=ba0f64a77b30db0415188ab69463a040",
                    "width": 966,
                    "height": 488
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-08-28T00:00:00",
                    "utc": "2025-08-27T22:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-01-01T02:30:00",
                    "utc": "2026-01-01T01:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1638948271399",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "17486957800",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1638948271399",
            "hide_end_date": true,
            "start_date": "2025-12-31",
            "end_time": "04:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "25.00",
                    "value": 2500,
                    "display": "25.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "8.00",
                    "value": 800,
                    "display": "8.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-01-01",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/16009",
                    "display_name": "New Years Eve"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/116",
                    "display_name": "Seasonal & Holiday",
                    "localized": {
                        "display_name": "Seasonal & Holiday"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/11",
                    "display_name": "Party or Social Gathering"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/celebration",
                    "display_name": "celebration",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/event",
                    "display_name": "event",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fire",
                    "display_name": "fire",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/silvesterparty",
                    "display_name": "silvesterparty",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/ruhpolding",
                    "display_name": "ruhpolding",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1638948271399",
            "start_time": "22:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Kurhausstraße 4",
                "venue_profile_id": null,
                "address": {
                    "city": "Ruhpolding",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.6468654",
                    "localized_address_display": "Kurhausstraße 4, 83324 Ruhpolding",
                    "postal_code": "83324",
                    "address_1": "Kurhausstraße 4",
                    "address_2": "",
                    "latitude": "47.76014079999999",
                    "localized_multi_line_address_display": [
                        "Kurhausstraße 4",
                        "83324 Ruhpolding"
                    ],
                    "localized_area_display": "Ruhpolding"
                },
                "venue_profile_url": "",
                "id": "284547093"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "WoWeBa Live GmbH",
                "profile_type": "organizer",
                "num_followers": 24,
                "url": "https://www.eventbrite.de/o/woweba-live-gmbh-17486957800",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": "alohapromotion",
                "num_collections": null,
                "id": "17486957800",
                "website_url": "http://www.aloha-promotion.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1107492803",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "284547093",
            "checkout_flow": "widget",
            "name": "Silvesterparty - Ruhpolding on Fire",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/silvesterparty-ruhpolding-on-fire-tickets-1638948271399",
            "hide_start_date": false,
            "summary": "Feier den Jahreswechsel bei unserer Silvesterparty - Ruhpolding on Fire mit Feuershow, Musik und guter Stimmung!",
            "is_online_event": false,
            "eid": "ewhpxpqf",
            "published": "2025-08-28T11:02:41Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": null,
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170435866%2F2619792714501%2F1%2Foriginal.20251107-192619?crop=focalpoint&fit=crop&w=512&auto=format%2Ccompress&q=75&sharp=10&fp-x=0.499&fp-y=0.164&s=409be031fcb4f4c454eca0eef51a2962",
                "id": "1170435866",
                "crop_mask": null,
                "aspect_ratio": "1",
                "focal_point": {
                    "y": 0.164,
                    "x": 0.499
                },
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170435866%2F2619792714501%2F1%2Foriginal.20251107-192619?auto=format%2Ccompress&q=75&sharp=10&s=bf223b71e15c84691fcae0fffe394914",
                    "width": 442,
                    "height": 442
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-10T00:00:00",
                    "utc": "2025-11-09T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-02-01T11:00:00",
                    "utc": "2026-02-01T10:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1965571211405",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "107316365611",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1965571211405",
            "hide_end_date": true,
            "start_date": "2026-02-01",
            "end_time": "13:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 1,
                    "collections": [
                        {
                            "status": "live",
                            "_type": "event_group",
                            "name": "Literatur- und Medienwoche 2026",
                            "relative_url": "/cc/literatur-und-medienwoche-2026-4429103",
                            "is_autocreated": false,
                            "absolute_url": "https://www.eventbrite.com/cc/literatur-und-medienwoche-2026-4429103",
                            "summary": "Karten über Gerlinde Bammler: gerlinde.bammler@schule-schloss-stein.de oder unter +49 8621 8001-122 /124",
                            "organization_id": "2619793580521",
                            "image_id": "1166226853",
                            "id": "4429103",
                            "organizer_id": "107316365611",
                            "event_ids": [
                                "1921352409779",
                                "1965571211405",
                                "1965577840232",
                                "1965581747920",
                                "1965584366753",
                                "1965587286486",
                                "1967713906263"
                            ],
                            "type": "collection",
                            "slug": "literatur-und-medienwoche-2026",
                            "is_private": false
                        }
                    ]
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "17.17",
                    "value": 1717,
                    "display": "17.17 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "15.03",
                    "value": 1503,
                    "display": "15.03 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-02-01",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/5010",
                    "display_name": "Comedy"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/105",
                    "display_name": "Performing & Visual Arts",
                    "localized": {
                        "display_name": "Performing & Visual Arts"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/6",
                    "display_name": "Concert or Performance"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/musik",
                    "display_name": "musik",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/verse",
                    "display_name": "verse",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/ringelnatz",
                    "display_name": "ringelnatz",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/morgenstern",
                    "display_name": "morgenstern",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/heiter",
                    "display_name": "heiter",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1965571211405",
            "start_time": "11:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Schule Schloss Stein",
                "venue_profile_id": null,
                "address": {
                    "city": "Traunreut",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.5465918",
                    "localized_address_display": "Schloßhof 1, 83371 Traunreut",
                    "postal_code": "83371",
                    "address_1": "Schloßhof 1",
                    "address_2": "",
                    "latitude": "47.9878118",
                    "localized_multi_line_address_display": [
                        "Schloßhof 1",
                        "83371 Traunreut"
                    ],
                    "localized_area_display": "Traunreut"
                },
                "venue_profile_url": "",
                "id": "276447333"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Schule Schloss Stein",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.de/o/schule-schloss-stein-107316365611",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "959959313",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "107316365611",
                "website_url": "https://www.schloss-stein.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1170435866",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "276447333",
            "checkout_flow": "widget",
            "name": "Ringelnatz und Morgenstern - heitere Verse mit Musik",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/ringelnatz-und-morgenstern-heitere-verse-mit-musik-tickets-1965571211405",
            "hide_start_date": false,
            "summary": "Die Veranstaltung umfasst Gedichte von Ringelnatz und Morgenstern, die mit musikalischer Begleitung (Klavier und Geige) dargeboten werden.",
            "is_online_event": false,
            "eid": "ewzyqmxh",
            "published": "2025-11-07T20:45:47Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#888478",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1171124901%2F2619792714501%2F1%2Foriginal.20251117-083516?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C360%2C878%2C439&s=a7781d52b363b05678e54cf3cbd5ed02",
                "id": "1171124901",
                "crop_mask": {
                    "width": 878,
                    "height": 439,
                    "top_left": {
                        "y": 360,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1171124901%2F2619792714501%2F1%2Foriginal.20251117-083516?auto=format%2Ccompress&q=75&sharp=10&s=8a8f40171c55b3f5215d1855dd0891f9",
                    "width": 878,
                    "height": 1280
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-10T00:00:00",
                    "utc": "2025-11-09T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-02-03T19:30:00",
                    "utc": "2026-02-03T18:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1967713906263",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "107316365611",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1967713906263",
            "hide_end_date": true,
            "start_date": "2026-02-03",
            "end_time": "22:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 1,
                    "collections": [
                        {
                            "status": "live",
                            "_type": "event_group",
                            "name": "Literatur- und Medienwoche 2026",
                            "relative_url": "/cc/literatur-und-medienwoche-2026-4429103",
                            "is_autocreated": false,
                            "absolute_url": "https://www.eventbrite.com/cc/literatur-und-medienwoche-2026-4429103",
                            "summary": "Karten über Gerlinde Bammler: gerlinde.bammler@schule-schloss-stein.de oder unter +49 8621 8001-122 /124",
                            "organization_id": "2619793580521",
                            "image_id": "1166226853",
                            "id": "4429103",
                            "organizer_id": "107316365611",
                            "event_ids": [
                                "1921352409779",
                                "1965571211405",
                                "1965577840232",
                                "1965581747920",
                                "1965584366753",
                                "1965587286486",
                                "1967713906263"
                            ],
                            "type": "collection",
                            "slug": "literatur-und-medienwoche-2026",
                            "is_private": false
                        }
                    ]
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "17.17",
                    "value": 1717,
                    "display": "17.17 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "13.96",
                    "value": 1396,
                    "display": "13.96 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-02-03",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/5009",
                    "display_name": "Literary Arts"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/105",
                    "display_name": "Performing & Visual Arts",
                    "localized": {
                        "display_name": "Performing & Visual Arts"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/2",
                    "display_name": "Seminar or Talk"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/discussion",
                    "display_name": "discussion",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/author",
                    "display_name": "author",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/lesung",
                    "display_name": "lesung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/gesprch",
                    "display_name": "gesprch",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/heribertprantl",
                    "display_name": "heribertprantl",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1967713906263",
            "start_time": "19:30",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Schule Schloss Stein",
                "venue_profile_id": null,
                "address": {
                    "city": "Traunreut",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.5465918",
                    "localized_address_display": "Schloßhof 1, 83371 Traunreut",
                    "postal_code": "83371",
                    "address_1": "Schloßhof 1",
                    "address_2": "",
                    "latitude": "47.9878118",
                    "localized_multi_line_address_display": [
                        "Schloßhof 1",
                        "83371 Traunreut"
                    ],
                    "localized_area_display": "Traunreut"
                },
                "venue_profile_url": "",
                "id": "276447333"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Schule Schloss Stein",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.de/o/schule-schloss-stein-107316365611",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "959959313",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "107316365611",
                "website_url": "https://www.schloss-stein.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1171124901",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "276447333",
            "checkout_flow": "widget",
            "name": "Heribert Prantl - Lesung mit Gespräch",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/heribert-prantl-lesung-mit-gesprach-tickets-1967713906263",
            "hide_start_date": false,
            "summary": "Wer den Frieden will, muss den Frieden vorbereiten. Alle reden vom Krieg, vom Frieden reden zu wenige: Die weißen Tauben sind müde.",
            "is_online_event": false,
            "eid": "ewzzctfc",
            "published": "2025-11-10T18:43:56Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#eeeeec",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1166221313%2F2619792714501%2F1%2Foriginal.20251030-093938?crop=focalpoint&fit=crop&w=512&auto=format%2Ccompress&q=75&sharp=10&fp-x=0.488&fp-y=0.3&s=38d43cf696f1ba9875209cf8cda917d6",
                "id": "1166221313",
                "crop_mask": null,
                "aspect_ratio": "0.875",
                "focal_point": {
                    "y": 0.3,
                    "x": 0.488
                },
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1166221313%2F2619792714501%2F1%2Foriginal.20251030-093938?auto=format%2Ccompress&q=75&sharp=10&s=cdd510c083d18809eb15bb5141ee975c",
                    "width": 2880,
                    "height": 3277
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-10T00:00:00",
                    "utc": "2025-11-09T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-01-30T19:30:00",
                    "utc": "2026-01-30T18:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1921352409779",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "107316365611",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1921352409779",
            "hide_end_date": true,
            "start_date": "2026-01-30",
            "end_time": "22:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 1,
                    "collections": [
                        {
                            "status": "live",
                            "_type": "event_group",
                            "name": "Literatur- und Medienwoche 2026",
                            "relative_url": "/cc/literatur-und-medienwoche-2026-4429103",
                            "is_autocreated": false,
                            "absolute_url": "https://www.eventbrite.com/cc/literatur-und-medienwoche-2026-4429103",
                            "summary": "Karten über Gerlinde Bammler: gerlinde.bammler@schule-schloss-stein.de oder unter +49 8621 8001-122 /124",
                            "organization_id": "2619793580521",
                            "image_id": "1166226853",
                            "id": "4429103",
                            "organizer_id": "107316365611",
                            "event_ids": [
                                "1921352409779",
                                "1965571211405",
                                "1965577840232",
                                "1965581747920",
                                "1965584366753",
                                "1965587286486",
                                "1967713906263"
                            ],
                            "type": "collection",
                            "slug": "literatur-und-medienwoche-2026",
                            "is_private": false
                        }
                    ]
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "31.01",
                    "value": 3101,
                    "display": "31.01 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "27.82",
                    "value": 2782,
                    "display": "27.82 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-01-30",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/5010",
                    "display_name": "Comedy"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/105",
                    "display_name": "Performing & Visual Arts",
                    "localized": {
                        "display_name": "Performing & Visual Arts"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/6",
                    "display_name": "Concert or Performance"
                }
            ],
            "eventbrite_event_id": "1921352409779",
            "start_time": "19:30",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Schule Schloss Stein",
                "venue_profile_id": null,
                "address": {
                    "city": "Traunreut",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.5465918",
                    "localized_address_display": "Schloßhof 1, 83371 Traunreut",
                    "postal_code": "83371",
                    "address_1": "Schloßhof 1",
                    "address_2": "",
                    "latitude": "47.9878118",
                    "localized_multi_line_address_display": [
                        "Schloßhof 1",
                        "83371 Traunreut"
                    ],
                    "localized_area_display": "Traunreut"
                },
                "venue_profile_url": "",
                "id": "276447333"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Schule Schloss Stein",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.de/o/schule-schloss-stein-107316365611",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "959959313",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "107316365611",
                "website_url": "https://www.schloss-stein.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1166221313",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "276447333",
            "checkout_flow": "widget",
            "name": "Michael Altinger",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/michael-altinger-tickets-1921352409779",
            "hide_start_date": false,
            "summary": "Kabarett\n»Die letzte Tasse Testosteron«\nEinlass: 19:00 Uhr | Beginn 19:30 Uhr",
            "is_online_event": false,
            "eid": "1921352409779",
            "published": "2025-11-07T20:54:56Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#422237",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1165680743%2F1129005953943%2F1%2Foriginal.jpg?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C0%2C2160%2C1080&s=82ed9560a9e9b6a6c7100f112081910f",
                "id": "1165680743",
                "crop_mask": {
                    "width": 2160,
                    "height": 1080,
                    "top_left": {
                        "y": 0,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1165680743%2F1129005953943%2F1%2Foriginal.jpg?auto=format%2Ccompress&q=75&sharp=10&s=0e9855afae333bf8df9049a51e3059bc",
                    "width": 2160,
                    "height": 1080
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-10-26T23:30:00",
                    "utc": "2025-10-26T22:30:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-12-01T17:00:00",
                    "utc": "2025-12-01T16:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1918056040249",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "52877025213",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1918056040249",
            "hide_end_date": false,
            "start_date": "2025-12-01",
            "end_time": "21:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "49.13",
                    "value": 4913,
                    "display": "49.13 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "49.13",
                    "value": 4913,
                    "display": "49.13 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-12-01",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/5009",
                    "display_name": "Literary Arts"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/105",
                    "display_name": "Performing & Visual Arts",
                    "localized": {
                        "display_name": "Performing & Visual Arts"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/9",
                    "display_name": "Class, Training, or Workshop"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/workshop",
                    "display_name": "workshop",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/creative",
                    "display_name": "creative",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/inspiration",
                    "display_name": "inspiration",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/storytelling",
                    "display_name": "storytelling",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/narrative",
                    "display_name": "narrative",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1918056040249",
            "start_time": "18:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Impact Hub Traunstein",
                "venue_profile_id": null,
                "address": {
                    "city": "Traunstein",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.6333246",
                    "localized_address_display": "Vonfichtstraße 1, 83278 Traunstein",
                    "postal_code": "83278",
                    "address_1": "Vonfichtstraße 1",
                    "address_2": "",
                    "latitude": "47.8638603",
                    "localized_multi_line_address_display": [
                        "Vonfichtstraße 1",
                        "83278 Traunstein"
                    ],
                    "localized_area_display": "Traunstein"
                },
                "venue_profile_url": "",
                "id": "294171013"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "WirFAIRbandelt",
                "profile_type": "organizer",
                "num_followers": 137,
                "url": "https://www.eventbrite.de/o/wirfairbandelt-52877025213",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "731428419",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "52877025213",
                "website_url": "https://frauenfairbandelt.net/",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1165680743",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "294171013",
            "checkout_flow": "widget",
            "name": "Storytelling Workshop",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/storytelling-workshop-tickets-1918056040249",
            "hide_start_date": false,
            "summary": "Die Kunst des Storytellings – deine Botschaft, die bleibt!",
            "is_online_event": false,
            "eid": "ewzbbrhc",
            "published": "2025-10-29T18:02:09Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#cbbccd",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170292725%2F2169019089493%2F1%2Foriginal.20251106-095553?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=102%2C14%2C1730%2C865&s=73570e9f6e3470ba2fc0488ec975ba26",
                "id": "1170292725",
                "crop_mask": {
                    "width": 1730,
                    "height": 865,
                    "top_left": {
                        "y": 14,
                        "x": 102
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170292725%2F2169019089493%2F1%2Foriginal.20251106-095553?auto=format%2Ccompress&q=75&sharp=10&s=05bccefc30d7a63ee67914a716241c01",
                    "width": 1920,
                    "height": 900
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-06T11:00:00",
                    "utc": "2025-11-06T10:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-28T18:00:00",
                    "utc": "2025-11-28T17:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Vienna",
            "id": "1963937640350",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "113677765871",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1963937640350",
            "hide_end_date": false,
            "start_date": "2025-11-28",
            "end_time": "21:30",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "45.90",
                    "value": 4590,
                    "display": "45.90 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "45.90",
                    "value": 4590,
                    "display": "45.90 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-28",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/5012",
                    "display_name": "Painting"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/105",
                    "display_name": "Performing & Visual Arts",
                    "localized": {
                        "display_name": "Performing & Visual Arts"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/9",
                    "display_name": "Class, Training, or Workshop"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/social",
                    "display_name": "social",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/relaxation",
                    "display_name": "relaxation",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fun",
                    "display_name": "fun",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/creativity",
                    "display_name": "creativity",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/drink_paint",
                    "display_name": "drink_paint",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1963937640350",
            "start_time": "18:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Stadtpl. 95",
                "venue_profile_id": null,
                "address": {
                    "city": "Burghausen",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.8342265",
                    "localized_address_display": "Stadtplatz 95, 84489 Burghausen",
                    "postal_code": "84489",
                    "address_1": "Stadtplatz 95",
                    "address_2": "",
                    "latitude": "48.1599425",
                    "localized_multi_line_address_display": [
                        "Stadtplatz 95",
                        "84489 Burghausen"
                    ],
                    "localized_area_display": "Burghausen"
                },
                "venue_profile_url": "",
                "id": "295080785"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Drink & Paint",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.de/o/drink-paint-113677765871",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "113677765871",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1170292725",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "295080785",
            "checkout_flow": "widget",
            "name": "Drink & Paint",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/drink-paint-tickets-1963937640350",
            "hide_start_date": false,
            "summary": "Dein kreativer Abend mit guter Laune, Drinks, Snacks & deinem eigenen Kunstwerk – alles in gemütlicher Atmosphäre im Altstadtcafé Burghausen",
            "is_online_event": false,
            "eid": "ewzydckt",
            "published": "2025-11-06T10:06:37Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#1d272c",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170809647%2F2934797550411%2F1%2Foriginal.20251112-184831?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C100%2C1200%2C600&s=f54f01fd5f5670dbc596d5377a624391",
                "id": "1170809647",
                "crop_mask": {
                    "width": 1200,
                    "height": 600,
                    "top_left": {
                        "y": 100,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170809647%2F2934797550411%2F1%2Foriginal.20251112-184831?auto=format%2Ccompress&q=75&sharp=10&s=7c979f3330fd591b583e230fe997bf18",
                    "width": 1200,
                    "height": 800
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-02T00:00:00",
                    "utc": "2025-11-01T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-01-01T18:00:00",
                    "utc": "2026-01-01T17:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1931438457419",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "119118113581",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1931438457419",
            "hide_end_date": false,
            "start_date": "2026-01-02",
            "end_time": "14:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "999.00",
                    "value": 99900,
                    "display": "999.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "249.00",
                    "value": 24900,
                    "display": "249.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-01-05",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/7005",
                    "display_name": "Yoga"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/107",
                    "display_name": "Health & Wellness",
                    "localized": {
                        "display_name": "Health & Wellness"
                    }
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/retreat",
                    "display_name": "retreat",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/klarheit",
                    "display_name": "klarheit",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/start",
                    "display_name": "start",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/ziel",
                    "display_name": "ziel",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/selbstvertrauen",
                    "display_name": "selbstvertrauen",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/selbstwirksamkeit",
                    "display_name": "selbstwirksamkeit",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/orientierung",
                    "display_name": "orientierung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/2026",
                    "display_name": "2026",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/verbindlichkeit",
                    "display_name": "verbindlichkeit",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/neujahrsretreat",
                    "display_name": "neujahrsretreat",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1931438457419",
            "start_time": "18:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Kloster Seeon",
                "venue_profile_id": null,
                "address": {
                    "city": "Seeon-Seebruck",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.4478947",
                    "localized_address_display": "Klosterweg 1, 83370 Seeon-Seebruck",
                    "postal_code": "83370",
                    "address_1": "Klosterweg 1",
                    "address_2": "",
                    "latitude": "47.9749746",
                    "localized_multi_line_address_display": [
                        "Klosterweg 1",
                        "83370 Seeon-Seebruck"
                    ],
                    "localized_area_display": "Seeon-Seebruck"
                },
                "venue_profile_url": "",
                "id": "294483863"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Der Koach",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.com/o/der-koach-119118113581",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "119118113581",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1170809647",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "294483863",
            "checkout_flow": "widget",
            "name": "3,5 Tage Neujahrs-Retreat – 2026 wird mein Jahr! (3 Übernachtungen)",
            "language": "de-de",
            "url": "https://www.eventbrite.com/e/35-tage-neujahrs-retreat-2026-wird-mein-jahr-3-ubernachtungen-registrierung-1931438457419",
            "hide_start_date": false,
            "summary": "Starte das neue Jahr mit einem klaren Fokus, konkreten Zielen und Verbindlichkeit beim Neujahrs-Retreat – 2026 wird dein Jahr!",
            "is_online_event": false,
            "eid": "ewzgkfdt",
            "published": "2025-10-31T09:56:41Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#000000",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170438571%2F2619792714501%2F1%2Foriginal.20251107-195322?crop=focalpoint&fit=crop&w=512&auto=format%2Ccompress&q=75&sharp=10&fp-x=0.609&fp-y=0.498&s=490162b0b2d870b37042790344c912ae",
                "id": "1170438571",
                "crop_mask": null,
                "aspect_ratio": "1.335",
                "focal_point": {
                    "y": 0.498,
                    "x": 0.609
                },
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170438571%2F2619792714501%2F1%2Foriginal.20251107-195322?auto=format%2Ccompress&q=75&sharp=10&s=858d0757308ead894ce7f9bc934af3d5",
                    "width": 2560,
                    "height": 1920
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-10T00:00:00",
                    "utc": "2025-11-09T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-02-05T19:30:00",
                    "utc": "2026-02-05T18:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1965584366753",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "107316365611",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1965584366753",
            "hide_end_date": true,
            "start_date": "2026-02-05",
            "end_time": "22:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 1,
                    "collections": [
                        {
                            "status": "live",
                            "_type": "event_group",
                            "name": "Literatur- und Medienwoche 2026",
                            "relative_url": "/cc/literatur-und-medienwoche-2026-4429103",
                            "is_autocreated": false,
                            "absolute_url": "https://www.eventbrite.com/cc/literatur-und-medienwoche-2026-4429103",
                            "summary": "Karten über Gerlinde Bammler: gerlinde.bammler@schule-schloss-stein.de oder unter +49 8621 8001-122 /124",
                            "organization_id": "2619793580521",
                            "image_id": "1166226853",
                            "id": "4429103",
                            "organizer_id": "107316365611",
                            "event_ids": [
                                "1921352409779",
                                "1965571211405",
                                "1965577840232",
                                "1965581747920",
                                "1965584366753",
                                "1965587286486",
                                "1967713906263"
                            ],
                            "type": "collection",
                            "slug": "literatur-und-medienwoche-2026",
                            "is_private": false
                        }
                    ]
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "20.36",
                    "value": 2036,
                    "display": "20.36 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "17.17",
                    "value": 1717,
                    "display": "17.17 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-02-05",
            "tags": [
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/103",
                    "display_name": "Music",
                    "localized": {
                        "display_name": "Music"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/6",
                    "display_name": "Concert or Performance"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/dance",
                    "display_name": "dance",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/music",
                    "display_name": "music",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/culture",
                    "display_name": "culture",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/bavaria",
                    "display_name": "bavaria",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/cubaboarisch",
                    "display_name": "cubaboarisch",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1965584366753",
            "start_time": "19:30",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Schule Schloss Stein",
                "venue_profile_id": null,
                "address": {
                    "city": "Traunreut",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.5465918",
                    "localized_address_display": "Schloßhof 1, 83371 Traunreut",
                    "postal_code": "83371",
                    "address_1": "Schloßhof 1",
                    "address_2": "",
                    "latitude": "47.9878118",
                    "localized_multi_line_address_display": [
                        "Schloßhof 1",
                        "83371 Traunreut"
                    ],
                    "localized_area_display": "Traunreut"
                },
                "venue_profile_url": "",
                "id": "276447333"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Schule Schloss Stein",
                "profile_type": "organizer",
                "num_followers": null,
                "url": "https://www.eventbrite.de/o/schule-schloss-stein-107316365611",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "959959313",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "107316365611",
                "website_url": "https://www.schloss-stein.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1170438571",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "276447333",
            "checkout_flow": "widget",
            "name": "CubaBoarisch 2.0",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/cubaboarisch-20-tickets-1965584366753",
            "hide_start_date": false,
            "summary": "Die musikalische Völkerverständigung zwischen Kuba und Bayern ist von den Festivals nicht mehr wegzudenken.",
            "is_online_event": false,
            "eid": "ewzyqpqz",
            "published": "2025-11-07T20:58:32Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#d2ddc1",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1157652203%2F2754305045531%2F1%2Foriginal.20251020-180543?w=300&auto=format%2Ccompress&q=75&sharp=10&s=4a786e5ae3f8ccc455620d48217cd882",
                "id": "1157652203",
                "crop_mask": null,
                "aspect_ratio": "1.5",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1157652203%2F2754305045531%2F1%2Foriginal.20251020-180543?auto=format%2Ccompress&q=75&sharp=10&s=12d551d098b311ec200032f5e0e5f5f3",
                    "width": 1800,
                    "height": 1200
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "1970-01-01T00:00:00",
                    "utc": "1969-12-31T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-19T23:00:00",
                    "utc": "2025-11-19T22:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Vienna",
            "id": "1917259287139",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "110903480871",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1917259287139",
            "hide_end_date": true,
            "start_date": "2025-11-19",
            "end_time": "00:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "3.99",
                    "value": 399,
                    "display": "3.99 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "3.99",
                    "value": 399,
                    "display": "3.99 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-20",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/9006",
                    "display_name": "Travel"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/109",
                    "display_name": "Travel & Outdoor",
                    "localized": {
                        "display_name": "Travel & Outdoor"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/16",
                    "display_name": "Tour"
                }
            ],
            "eventbrite_event_id": "1917259287139",
            "start_time": "12:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Laufen Castle (Germany)",
                "venue_profile_id": null,
                "address": {
                    "city": "Laufen",
                    "country": "DE",
                    "region": "",
                    "longitude": "12.936789",
                    "localized_address_display": "Laufen Castle (Germany) 1 Schloßpl., 83410 Laufen",
                    "postal_code": "83410",
                    "address_1": "Laufen Castle (Germany)",
                    "address_2": "1 Schloßpl.",
                    "latitude": "47.9384746",
                    "localized_multi_line_address_display": [
                        "Laufen Castle (Germany)",
                        "1 Schloßpl.",
                        "83410 Laufen"
                    ],
                    "localized_area_display": "Laufen"
                },
                "venue_profile_url": "",
                "id": "285349493"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "AudaTours Audio Tours",
                "profile_type": "organizer",
                "num_followers": 2431,
                "url": "https://www.eventbrite.co.uk/o/audatours-audio-tours-110903480871",
                "twitter": null,
                "summary": "AudaTours is a self-guided audio tour platform designed for curious, independent explorers.We create immersive, GPS-triggered walking tours that bring cities to life through storytelling. Our mission ...",
                "num_saves": null,
                "image_id": "1024248593",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "110903480871",
                "website_url": "https://audatours.com",
                "num_following": null
            },
            "series_id": "1658157065419",
            "image_id": "1157652203",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "285349493",
            "checkout_flow": "widget",
            "name": "Laufen Audio Tour: Castles, Crossings, and Hidden Sanctuaries of Obslaufen",
            "language": "en-us",
            "url": "https://www.eventbrite.co.uk/e/laufen-audio-tour-castles-crossings-and-hidden-sanctuaries-of-obslaufen-tickets-1917259287139",
            "hide_start_date": true,
            "summary": "Self-guided audio tour of Obslaufen, Laufen. Explore the area while listening to fascinating stories.",
            "is_online_event": false,
            "eid": "1917259287139",
            "published": "2025-09-06T21:00:16Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#235d91",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1139833793%2F264242427116%2F1%2Foriginal.20250930-103000?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C136%2C1784%2C892&s=30a1a252aa4b689ca9daef6a437168f4",
                "id": "1139833793",
                "crop_mask": {
                    "width": 1784,
                    "height": 892,
                    "top_left": {
                        "y": 136,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1139833793%2F264242427116%2F1%2Foriginal.20250930-103000?auto=format%2Ccompress&q=75&sharp=10&s=b5510d791f4a22965c85935565d7a615",
                    "width": 1784,
                    "height": 1312
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-09-30T15:00:00",
                    "utc": "2025-09-30T13:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-09-04T15:00:00",
                    "utc": "2026-09-04T13:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1756495578559",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "17589548946",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1756495578559",
            "hide_end_date": false,
            "start_date": "2026-09-05",
            "end_time": "23:30",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "58.71",
                    "value": 5871,
                    "display": "58.71 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "42.74",
                    "value": 4274,
                    "display": "42.74 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-09-05",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/3013",
                    "display_name": "Pop"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/103",
                    "display_name": "Music",
                    "localized": {
                        "display_name": "Music"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/11",
                    "display_name": "Party or Social Gathering"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/traunstein",
                    "display_name": "traunstein",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/chiemsee",
                    "display_name": "chiemsee",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/prien",
                    "display_name": "prien",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/libella",
                    "display_name": "libella",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/tanzschiff",
                    "display_name": "tanzschiff",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/trostberg",
                    "display_name": "trostberg",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/chieming",
                    "display_name": "chieming",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/altenmarkt",
                    "display_name": "altenmarkt",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/cafelibella",
                    "display_name": "cafelibella",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/seebruck",
                    "display_name": "seebruck",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1756495578559",
            "start_time": "19:30",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Dampfersteg",
                "venue_profile_id": null,
                "address": {
                    "city": "Bernau am Chiemsee",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.38429999999994",
                    "localized_address_display": "Felden Rasthausstraße 11, 83233 Bernau am Chiemsee",
                    "postal_code": "83233",
                    "address_1": "Felden",
                    "address_2": "Rasthausstraße 11",
                    "latitude": "47.83115",
                    "localized_multi_line_address_display": [
                        "Felden",
                        "Rasthausstraße 11",
                        "83233 Bernau am Chiemsee"
                    ],
                    "localized_area_display": "Bernau am Chiemsee"
                },
                "venue_profile_url": "",
                "id": "289516653"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Café Libella",
                "profile_type": "organizer",
                "num_followers": 104,
                "url": "https://www.eventbrite.de/o/cafe-libella-17589548946",
                "twitter": null,
                "summary": "Subkultur und Szeneclub seit 1983. Musikalisch stark im Bereich 80s-Wave, Rockabilly, Ska & Indie.",
                "num_saves": null,
                "image_id": "810085829",
                "followed_by_you": false,
                "facebook": "events/423550151534384",
                "num_collections": null,
                "id": "17589548946",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1139833793",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "289516653",
            "checkout_flow": "widget",
            "name": "Libella Tanzschiff 2026",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/libella-tanzschiff-2026-tickets-1756495578559",
            "hide_start_date": false,
            "summary": "Das Libella Tanzschiff - Das Orginal!",
            "is_online_event": false,
            "eid": "ewrmmzzm",
            "published": "2025-09-30T12:40:44Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#999c90",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044688043%2F257411956186%2F1%2Foriginal.20250603-122512?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C300%2C1200%2C600&s=3f9e4749ea86d3f93e971ccdcd8bb1bf",
                "id": "1044688043",
                "crop_mask": {
                    "width": 1200,
                    "height": 600,
                    "top_left": {
                        "y": 300,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044688043%2F257411956186%2F1%2Foriginal.20250603-122512?auto=format%2Ccompress&q=75&sharp=10&s=a757c1e8a0b58e85c68f53caec3e5835",
                    "width": 1200,
                    "height": 1200
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-06-03T00:00:00",
                    "utc": "2025-06-02T22:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-04-13T11:00:00",
                    "utc": "2026-04-13T09:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1394484694259",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "54778754783",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1394484694259",
            "hide_end_date": false,
            "start_date": "2026-04-14",
            "end_time": "17:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-04-14",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/1010",
                    "display_name": "Career"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/101",
                    "display_name": "Business & Professional",
                    "localized": {
                        "display_name": "Business & Professional"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/2",
                    "display_name": "Seminar or Talk"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/event",
                    "display_name": "event",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/retreat",
                    "display_name": "retreat",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/executives",
                    "display_name": "executives",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/leadershipskills",
                    "display_name": "leadershipskills",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/besinnung",
                    "display_name": "besinnung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kloster",
                    "display_name": "kloster",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fuehrung",
                    "display_name": "fuehrung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/klar",
                    "display_name": "klar",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/nlp_training",
                    "display_name": "nlp_training",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kommunikationsstrategien",
                    "display_name": "kommunikationsstrategien",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1394484694259",
            "start_time": "11:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Kloster Seeon",
                "venue_profile_id": null,
                "address": {
                    "city": "Seeon-Seebruck",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.4478947",
                    "localized_address_display": "Klosterweg 1, 83370 Seeon-Seebruck",
                    "postal_code": "83370",
                    "address_1": "Klosterweg 1",
                    "address_2": "",
                    "latitude": "47.9749746",
                    "localized_multi_line_address_display": [
                        "Klosterweg 1",
                        "83370 Seeon-Seebruck"
                    ],
                    "localized_area_display": "Seeon-Seebruck"
                },
                "venue_profile_url": "",
                "id": "273046043"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Cornelia Siegmann",
                "profile_type": "organizer",
                "num_followers": 106,
                "url": "https://www.eventbrite.de/o/cornelia-siegmann-54778754783",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "54778754783",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1044688043",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "273046043",
            "checkout_flow": "widget",
            "name": "Retreat im Kloster_ Führung braucht Richtung",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/retreat-im-kloster-fuhrung-braucht-richtung-tickets-1394484694259",
            "hide_start_date": false,
            "summary": "Führung beginnt nicht mit Reden oder Reagieren.\nSondern mit innerer Ausrichtung.\nDieses Retreat bietet Raum für Stille, Struktur + Rückblick",
            "is_online_event": false,
            "eid": "etrhxgwf",
            "published": "2025-06-03T12:20:35Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#efe9dd",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044697483%2F257411956186%2F1%2Foriginal.20250603-124030?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C300%2C1200%2C600&s=d4d620f1160e3d96633e148c765c775e",
                "id": "1044697483",
                "crop_mask": {
                    "width": 1200,
                    "height": 600,
                    "top_left": {
                        "y": 300,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044697483%2F257411956186%2F1%2Foriginal.20250603-124030?auto=format%2Ccompress&q=75&sharp=10&s=bba28806986b5beb6c9411a5afdcf5e4",
                    "width": 1200,
                    "height": 1200
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-06-03T00:00:00",
                    "utc": "2025-06-02T22:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2026-10-10T11:00:00",
                    "utc": "2026-10-10T09:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1394549096889",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "54778754783",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1394549096889",
            "hide_end_date": false,
            "start_date": "2026-10-13",
            "end_time": "17:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-10-13",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/1010",
                    "display_name": "Career"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/101",
                    "display_name": "Business & Professional",
                    "localized": {
                        "display_name": "Business & Professional"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/2",
                    "display_name": "Seminar or Talk"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/event",
                    "display_name": "event",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/retreat",
                    "display_name": "retreat",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/executives",
                    "display_name": "executives",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/leadershipskills",
                    "display_name": "leadershipskills",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/besinnung",
                    "display_name": "besinnung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kloster",
                    "display_name": "kloster",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fuehrung",
                    "display_name": "fuehrung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/klar",
                    "display_name": "klar",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/nlp_training",
                    "display_name": "nlp_training",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kommunikationsstrategien",
                    "display_name": "kommunikationsstrategien",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1394549096889",
            "start_time": "11:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Kloster Seeon",
                "venue_profile_id": null,
                "address": {
                    "city": "Seeon-Seebruck",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.4478947",
                    "localized_address_display": "Klosterweg 1, 83370 Seeon-Seebruck",
                    "postal_code": "83370",
                    "address_1": "Klosterweg 1",
                    "address_2": "",
                    "latitude": "47.9749746",
                    "localized_multi_line_address_display": [
                        "Klosterweg 1",
                        "83370 Seeon-Seebruck"
                    ],
                    "localized_area_display": "Seeon-Seebruck"
                },
                "venue_profile_url": "",
                "id": "273048973"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Cornelia Siegmann",
                "profile_type": "organizer",
                "num_followers": 106,
                "url": "https://www.eventbrite.de/o/cornelia-siegmann-54778754783",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "54778754783",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1044697483",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "273048973",
            "checkout_flow": "widget",
            "name": "Retreat im Kloster_ Führung braucht  Rückgrat",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/retreat-im-kloster-fuhrung-braucht-ruckgrat-tickets-1394549096889",
            "hide_start_date": false,
            "summary": "Führung beginnt nicht mit Reden oder Reagieren.\nSondern mit innerer Ausrichtung.\nDieses Retreat bietet Raum für Stille, Struktur + Rückblick",
            "is_online_event": false,
            "eid": "etrhxzyh",
            "published": "2025-06-03T12:44:00Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#999c90",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044688043%2F257411956186%2F1%2Foriginal.20250603-122512?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C300%2C1200%2C600&s=3f9e4749ea86d3f93e971ccdcd8bb1bf",
                "id": "1044688043",
                "crop_mask": {
                    "width": 1200,
                    "height": 600,
                    "top_left": {
                        "y": 300,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1044688043%2F257411956186%2F1%2Foriginal.20250603-122512?auto=format%2Ccompress&q=75&sharp=10&s=a757c1e8a0b58e85c68f53caec3e5835",
                    "width": 1200,
                    "height": 1200
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-06-03T00:00:00",
                    "utc": "2025-06-02T22:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2027-04-17T11:00:00",
                    "utc": "2027-04-17T09:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1394585044409",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "54778754783",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1394585044409",
            "hide_end_date": false,
            "start_date": "2027-04-20",
            "end_time": "17:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "7246.24",
                    "value": 724624,
                    "display": "7246.24 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2027-04-20",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/1010",
                    "display_name": "Career"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/101",
                    "display_name": "Business & Professional",
                    "localized": {
                        "display_name": "Business & Professional"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/2",
                    "display_name": "Seminar or Talk"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/event",
                    "display_name": "event",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/retreat",
                    "display_name": "retreat",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/executives",
                    "display_name": "executives",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/leadershipskills",
                    "display_name": "leadershipskills",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/besinnung",
                    "display_name": "besinnung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kloster",
                    "display_name": "kloster",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fuehrung",
                    "display_name": "fuehrung",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/klar",
                    "display_name": "klar",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/nlp_training",
                    "display_name": "nlp_training",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kommunikationsstrategien",
                    "display_name": "kommunikationsstrategien",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1394585044409",
            "start_time": "11:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Kloster Seeon",
                "venue_profile_id": null,
                "address": {
                    "city": "Seeon-Seebruck",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.4478947",
                    "localized_address_display": "Klosterweg 1, 83370 Seeon-Seebruck",
                    "postal_code": "83370",
                    "address_1": "Klosterweg 1",
                    "address_2": "",
                    "latitude": "47.9749746",
                    "localized_multi_line_address_display": [
                        "Klosterweg 1",
                        "83370 Seeon-Seebruck"
                    ],
                    "localized_area_display": "Seeon-Seebruck"
                },
                "venue_profile_url": "",
                "id": "273049923"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Cornelia Siegmann",
                "profile_type": "organizer",
                "num_followers": 106,
                "url": "https://www.eventbrite.de/o/cornelia-siegmann-54778754783",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "54778754783",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1044688043",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "273049923",
            "checkout_flow": "widget",
            "name": "Retreat im Kloster_ Führung braucht  Durchsetzungskraf",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/retreat-im-kloster-fuhrung-braucht-durchsetzungskraf-tickets-1394585044409",
            "hide_start_date": false,
            "summary": "Führung beginnt nicht mit Reden oder Reagieren.\nSondern mit innerer Ausrichtung.\nDieses Retreat bietet Raum für Stille, Struktur + Rückblick",
            "is_online_event": false,
            "eid": "etrhyfpm",
            "published": "2025-06-03T12:49:00Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#381b17",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1167203993%2F1863712942473%2F1%2Foriginal.20251031-114923?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C398%2C5184%2C2592&s=92059ea49f7403b01494ddb796a76cb1",
                "id": "1167203993",
                "crop_mask": {
                    "width": 5184,
                    "height": 2592,
                    "top_left": {
                        "y": 398,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1167203993%2F1863712942473%2F1%2Foriginal.20251031-114923?auto=format%2Ccompress&q=75&sharp=10&s=4d2e3e995feb078a17f7273bd10c9a8c",
                    "width": 5184,
                    "height": 3456
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-15T00:00:00",
                    "utc": "2025-11-14T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-20T23:30:00",
                    "utc": "2025-11-20T22:30:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Vienna",
            "id": "1735765644769",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "74017892583",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1735765644769",
            "hide_end_date": false,
            "start_date": "2025-11-22",
            "end_time": "23:59",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "42.00",
                    "value": 4200,
                    "display": "42.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "30.00",
                    "value": 3000,
                    "display": "30.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-22",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/16006",
                    "display_name": "Christmas"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/116",
                    "display_name": "Seasonal & Holiday",
                    "localized": {
                        "display_name": "Seasonal & Holiday"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/6",
                    "display_name": "Concert or Performance"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/event",
                    "display_name": "event",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/show",
                    "display_name": "show",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/tradition",
                    "display_name": "tradition",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/krampusshow",
                    "display_name": "krampusshow",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/latschenwirt",
                    "display_name": "latschenwirt",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1735765644769",
            "start_time": "18:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Latschenwirt Gastronomie GmbH",
                "venue_profile_id": null,
                "address": {
                    "city": "Großgmain",
                    "country": "AT",
                    "region": "Salzburg",
                    "longitude": "12.9490678",
                    "localized_address_display": "122 Buchhöhstraße, 5084 Großgmain",
                    "postal_code": "5084",
                    "address_1": "122 Buchhöhstraße",
                    "address_2": "",
                    "latitude": "47.73578329999999",
                    "localized_multi_line_address_display": [
                        "122 Buchhöhstraße",
                        "5084 Großgmain"
                    ],
                    "localized_area_display": "Großgmain"
                },
                "venue_profile_url": "",
                "id": "288565973"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Latschenwirt Gastronomie GmbH",
                "profile_type": "organizer",
                "num_followers": 43,
                "url": "https://www.eventbrite.at/o/latschenwirt-gastronomie-gmbh-74017892583",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "637547339",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "74017892583",
                "website_url": "http://www.latschenwirt.at",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1167203993",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "288565973",
            "checkout_flow": "widget",
            "name": "Krampusshow Latschenwirt #2",
            "language": "de-de",
            "url": "https://www.eventbrite.at/e/krampusshow-latschenwirt-2-tickets-1735765644769",
            "hide_start_date": false,
            "summary": "Tradition trifft auf moderne Inszenierung bei der Krampusshow \"Diabolus in Carne\" beim Latschenwirt mit über 100 Darstellern",
            "is_online_event": false,
            "eid": "ewqpdymt",
            "published": "2025-10-08T10:00:10Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#ebeae5",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F998650273%2F249364650624%2F1%2Foriginal.20250331-095924?w=512&auto=format%2Ccompress&q=75&sharp=10&rect=0%2C80%2C1280%2C640&s=0c2cc606b4e556d6cea5223b0753a11a",
                "id": "998650273",
                "crop_mask": {
                    "width": 1280,
                    "height": 640,
                    "top_left": {
                        "y": 80,
                        "x": 0
                    }
                },
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F998650273%2F249364650624%2F1%2Foriginal.20250331-095924?auto=format%2Ccompress&q=75&sharp=10&s=66250dec4de21c06783f861b20a51e00",
                    "width": 1280,
                    "height": 720
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-04-01T23:59:00",
                    "utc": "2025-04-01T21:59:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-12-30T14:00:00",
                    "utc": "2025-12-30T13:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1306691562639",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "19107615544",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1306691562639",
            "hide_end_date": false,
            "start_date": "2025-12-30",
            "end_time": "12:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "260.00",
                    "value": 26000,
                    "display": "260.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "130.00",
                    "value": 13000,
                    "display": "130.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2026-01-02",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/15001",
                    "display_name": "Education"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/115",
                    "display_name": "Family & Education",
                    "localized": {
                        "display_name": "Family & Education"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/18",
                    "display_name": "Camp, Trip, or Retreat"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/camp",
                    "display_name": "camp",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/achtsamkeit",
                    "display_name": "achtsamkeit",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/eltern",
                    "display_name": "eltern",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/kinder",
                    "display_name": "kinder",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/silvester",
                    "display_name": "silvester",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1306691562639",
            "start_time": "14:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Evang. Jugendbildungshaus Wiedhölzl-Kaser",
                "venue_profile_id": null,
                "address": {
                    "city": "Reit im Winkl",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.5504528",
                    "localized_address_display": "Seewiese 6, 83242 Reit im Winkl",
                    "postal_code": "83242",
                    "address_1": "Seewiese 6",
                    "address_2": "",
                    "latitude": "47.6738096",
                    "localized_multi_line_address_display": [
                        "Seewiese 6",
                        "83242 Reit im Winkl"
                    ],
                    "localized_area_display": "Reit im Winkl"
                },
                "venue_profile_url": "",
                "id": "263307673"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Merle Zirk",
                "profile_type": "organizer",
                "num_followers": 106,
                "url": "https://www.eventbrite.de/o/merle-zirk-19107615544",
                "twitter": null,
                "summary": "Als Trainerin für Ernährung, Persönlichkeitsentwicklung, gesundem Lebensstil, giftfreie Körperpflege und „Fair, crueltyfree Fashion“ ist es mein Anliegen, Menschen in ihren persönlichen Themen zu insp...",
                "num_saves": null,
                "image_id": "58659184",
                "followed_by_you": false,
                "facebook": "merlezirk.happygoluckyme",
                "num_collections": null,
                "id": "19107615544",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "998650273",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "263307673",
            "checkout_flow": "widget",
            "name": "Achtsames Silvester Camp für Kinder & Eltern",
            "language": "en-us",
            "url": "https://www.eventbrite.de/e/achtsames-silvester-camp-fur-kinder-eltern-tickets-1306691562639",
            "hide_start_date": false,
            "summary": "Gemeinsam das neue Jahr achtsam willkommen heißen - für kleine & große Entdecker. Nature & Family unlimited.",
            "is_online_event": false,
            "eid": "etcxbrxh",
            "published": "2025-04-02T07:50:52Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#ff48a4",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1109802383%2F278029250018%2F1%2Foriginal.20250831-102643?w=400&auto=format%2Ccompress&q=75&sharp=10&s=661adfac2c137703715a66946669cb70",
                "id": "1109802383",
                "crop_mask": null,
                "aspect_ratio": "2",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1109802383%2F278029250018%2F1%2Foriginal.20250831-102643?auto=format%2Ccompress&q=75&sharp=10&s=dc11474cdff95f82326b389d04f045f1",
                    "width": 2160,
                    "height": 1080
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-09-01T21:00:00",
                    "utc": "2025-09-01T19:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Berlin",
                    "local": "2025-11-24T11:00:00",
                    "utc": "2025-11-24T10:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Berlin",
            "id": "1647753076809",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "41553702943",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1647753076809",
            "hide_end_date": false,
            "start_date": "2025-11-29",
            "end_time": "15:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "81.09",
                    "value": 8109,
                    "display": "81.09 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "36.34",
                    "value": 3634,
                    "display": "36.34 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-30",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/6001",
                    "display_name": "Fashion"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/106",
                    "display_name": "Fashion & Beauty",
                    "localized": {
                        "display_name": "Fashion & Beauty"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/3",
                    "display_name": "Tradeshow, Consumer Show, or Expo"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/fashion",
                    "display_name": "fashion",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/clothing",
                    "display_name": "clothing",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/beauty",
                    "display_name": "beauty",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/sale",
                    "display_name": "sale",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/vintage",
                    "display_name": "vintage",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/rosenheim",
                    "display_name": "rosenheim",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1647753076809",
            "start_time": "15:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "AuerBräu Festhalle",
                "venue_profile_id": null,
                "address": {
                    "city": "Rosenheim",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.1251855",
                    "localized_address_display": "Kapuzinerweg, 83022 Rosenheim",
                    "postal_code": "83022",
                    "address_1": "Kapuzinerweg",
                    "address_2": "",
                    "latitude": "47.8598356",
                    "localized_multi_line_address_display": [
                        "Kapuzinerweg",
                        "83022 Rosenheim"
                    ],
                    "localized_area_display": "Rosenheim"
                },
                "venue_profile_url": "",
                "id": "284910553"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "Fetzer GmbH",
                "profile_type": "organizer",
                "num_followers": 623,
                "url": "https://www.eventbrite.de/o/fetzer-gmbh-41553702943",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": "920193383",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "41553702943",
                "website_url": "https://www.fetzer-veranstaltungen.de",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1109802383",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "284910553",
            "checkout_flow": "widget",
            "name": "Mädelsflohmarkt Rosenheim AUERBRÄU FESTHALLE  Sa. 29. & So. 30.11.25!",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/madelsflohmarkt-rosenheim-auerbrau-festhalle-sa-29-so-301125-tickets-1647753076809",
            "hide_start_date": false,
            "summary": "Premiere! ZWEI TAGE Mädelsflohmarkt in der Auerbräu Festhalle in Rosenheim!",
            "is_online_event": false,
            "eid": "ewhyrtth",
            "published": "2025-08-31T10:41:35Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#d9d6d2",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170881671%2F1406071732543%2F1%2Foriginal.20251113-150523?crop=focalpoint&fit=crop&w=512&auto=format%2Ccompress&q=75&sharp=10&fp-x=0.5&fp-y=0.5&s=399359a24e26fc37a9311bec40e923e1",
                "id": "1170881671",
                "crop_mask": null,
                "aspect_ratio": "2",
                "focal_point": {
                    "y": 0.5,
                    "x": 0.5
                },
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1170881671%2F1406071732543%2F1%2Foriginal.20251113-150523?auto=format%2Ccompress&q=75&sharp=10&s=501677438345a6a60ecc4bacc5815bfa",
                    "width": 1280,
                    "height": 640
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "start_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-13T00:00:00",
                    "utc": "2025-11-12T23:00:00Z"
                },
                "end_sales_date": {
                    "timezone": "Europe/Vienna",
                    "local": "2025-11-29T18:00:00",
                    "utc": "2025-11-29T17:00:00Z"
                },
                "default_message": null,
                "sales_status": "on_sale",
                "currency": "EUR",
                "message_code": null,
                "message": null,
                "message_type": null
            },
            "timezone": "Europe/Vienna",
            "id": "1969349072088",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "61188259433",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1969349072088",
            "hide_end_date": false,
            "start_date": "2025-11-29",
            "end_time": "23:30",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "11.85",
                    "value": 1185,
                    "display": "11.85 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "11.85",
                    "value": 1185,
                    "display": "11.85 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": true,
                "is_sold_out": false
            },
            "end_date": "2025-11-29",
            "tags": [
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/103",
                    "display_name": "Music",
                    "localized": {
                        "display_name": "Music"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/11",
                    "display_name": "Party or Social Gathering"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/salzburg",
                    "display_name": "salzburg",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/dance_party",
                    "display_name": "dance_party",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/house_music",
                    "display_name": "house_music",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/workout_party",
                    "display_name": "workout_party",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1969349072088",
            "start_time": "18:00",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "King Kong Club",
                "venue_profile_id": null,
                "address": {
                    "city": "Salzburg",
                    "country": "AT",
                    "region": "",
                    "longitude": "13.026931",
                    "localized_address_display": "Schumacherstraße 14, 5020 Salzburg",
                    "postal_code": "5020",
                    "address_1": "Schumacherstraße 14",
                    "address_2": "",
                    "latitude": "47.8134033",
                    "localized_multi_line_address_display": [
                        "Schumacherstraße 14",
                        "5020 Salzburg"
                    ],
                    "localized_area_display": "Salzburg"
                },
                "venue_profile_url": "",
                "id": "218837699"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "King Kong Club Salzburg",
                "profile_type": "organizer",
                "num_followers": 12,
                "url": "https://www.eventbrite.at/o/king-kong-club-salzburg-61188259433",
                "twitter": null,
                "summary": "Get ready for EVERYBODYBUILDINGRaus aus der Komfortzone, rein ins Erlebnis! Ob Krafttraining, High-intensity functional training, Boxtraining, Yoga oder einfach, um dich wohlzufühlen – deine Ziele sin...",
                "num_saves": null,
                "image_id": "1170882571",
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "61188259433",
                "website_url": "https://kingkong.club/",
                "num_following": null
            },
            "series_id": null,
            "image_id": "1170881671",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "218837699",
            "checkout_flow": "widget",
            "name": "LET'S GET PHYSICAL",
            "language": "en-us",
            "url": "https://www.eventbrite.at/e/lets-get-physical-tickets-1969349072088",
            "hide_start_date": false,
            "summary": "Get ready to move! King Kong Club x LaFam present “Let’s Get Physical” — party vibes, drinks by Brunos.",
            "is_online_event": false,
            "eid": "ewzzzfrg",
            "published": "2025-11-13T15:06:07Z"
        },
        {
            "image": {
                "edge_color_set": true,
                "edge_color": "#625f60",
                "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1157531973%2F554704728509%2F1%2Foriginal.20251020-124458?w=355&auto=format%2Ccompress&q=75&sharp=10&s=b93394a0392e8762975a14f7c15adc6f",
                "id": "1157531973",
                "crop_mask": null,
                "aspect_ratio": "1.775",
                "focal_point": null,
                "original": {
                    "url": "https://img.evbuc.com/https%3A%2F%2Fcdn.evbuc.com%2Fimages%2F1157531973%2F554704728509%2F1%2Foriginal.20251020-124458?auto=format%2Ccompress&q=75&sharp=10&s=8032bd82842336d45cd1077d1e200880",
                    "width": 1920,
                    "height": 1080
                }
            },
            "saves": {
                "saved_by_you": false
            },
            "event_sales_status": {
                "default_message": "Ausverkauft",
                "sales_status": "sold_out",
                "currency": "EUR",
                "message_code": "tickets_sold_out",
                "message": "Ausverkauft",
                "message_type": "default"
            },
            "timezone": "Europe/Berlin",
            "id": "1848747957909",
            "privacy_setting": "unlocked",
            "tickets_by": "Eventbrite",
            "primary_organizer_id": "33781231849",
            "tickets_url": "https://www.eventbrite.com/checkout-external?eid=1848747957909",
            "hide_end_date": false,
            "start_date": "2025-11-22",
            "end_time": "18:00",
            "status": "live",
            "_type": "destination_event",
            "public_collections": {
                "creator_collections": {
                    "object_count": 0,
                    "collections": []
                }
            },
            "ticket_availability": {
                "maximum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "12.00",
                    "value": 1200,
                    "display": "12.00 EUR"
                },
                "minimum_ticket_price": {
                    "currency": "EUR",
                    "major_value": "12.00",
                    "value": 1200,
                    "display": "12.00 EUR"
                },
                "is_free": false,
                "has_bogo_tickets": false,
                "has_available_tickets": false,
                "is_sold_out": true
            },
            "end_date": "2025-11-22",
            "tags": [
                {
                    "prefix": "EventbriteSubCategory",
                    "tag": "EventbriteSubCategory/4002",
                    "display_name": "Film"
                },
                {
                    "prefix": "EventbriteCategory",
                    "tag": "EventbriteCategory/104",
                    "display_name": "Film, Media & Entertainment",
                    "localized": {
                        "display_name": "Film, Media & Entertainment"
                    }
                },
                {
                    "prefix": "EventbriteFormat",
                    "tag": "EventbriteFormat/9",
                    "display_name": "Class, Training, or Workshop"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/workshop",
                    "display_name": "workshop",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/filme",
                    "display_name": "filme",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/stopmotion",
                    "display_name": "stopmotion",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/rosenheim",
                    "display_name": "rosenheim",
                    "prefix": "OrganizerTag"
                },
                {
                    "_type": "tag",
                    "tag": "OrganizerTag/trickfilmfestival",
                    "display_name": "trickfilmfestival",
                    "prefix": "OrganizerTag"
                }
            ],
            "eventbrite_event_id": "1848747957909",
            "start_time": "14:30",
            "primary_venue": {
                "_type": "destination_venue",
                "name": "Stadtbibliothek Rosenheim",
                "venue_profile_id": null,
                "address": {
                    "city": "Rosenheim",
                    "country": "DE",
                    "region": "BY",
                    "longitude": "12.1274679",
                    "localized_address_display": "Am Salzstadel 15, 83022 Rosenheim",
                    "postal_code": "83022",
                    "address_1": "Am Salzstadel 15",
                    "address_2": "",
                    "latitude": "47.8575574",
                    "localized_multi_line_address_display": [
                        "Am Salzstadel 15",
                        "83022 Rosenheim"
                    ],
                    "localized_area_display": "Rosenheim"
                },
                "venue_profile_url": "",
                "id": "292623143"
            },
            "primary_organizer": {
                "_type": "destination_profile",
                "num_upcoming_events": null,
                "name": "KulturKlub Rosenheim e. V.",
                "profile_type": "organizer",
                "num_followers": 11,
                "url": "https://www.eventbrite.de/o/kulturklub-rosenheim-e-v-33781231849",
                "twitter": null,
                "summary": null,
                "num_saves": null,
                "image_id": null,
                "followed_by_you": false,
                "facebook": null,
                "num_collections": null,
                "id": "33781231849",
                "website_url": null,
                "num_following": null
            },
            "series_id": null,
            "image_id": "1157531973",
            "is_protected_event": false,
            "is_cancelled": null,
            "primary_venue_id": "292623143",
            "checkout_flow": "widget",
            "name": "Trickfilmfestival Rosenheim // Workshop für Stop-Motion-Filme",
            "language": "de-de",
            "url": "https://www.eventbrite.de/e/trickfilmfestival-rosenheim-workshop-fur-stop-motion-filme-tickets-1848747957909",
            "hide_start_date": false,
            "summary": "Entdecke die Welt und Techniken der Stop-Motion-Filme!",
            "is_online_event": false,
            "eid": "ewxpckmt",
            "published": "2025-10-21T06:17:24Z"
        }
    ]
}


*/
