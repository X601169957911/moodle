---
layout: docs
title: "Course Activities"
description: "The course activity list is displayed on the course page. Each activity container has a number of possible interactions"
date: 2020-01-14T16:32:24+01:00
draft: false
weight: 20
toc: true
tags:
- Available
---

## Basic Activity
{{< mustache template="tool_componentlibrary/activity-item">}}
{
    "activityname": "Hello kitty forum",
    "modname": "Forum",
    "hasname": "true",
    "hasurl": "true",
    "icon": "http://placekitten.com/50/50"
}
{{< /mustache >}}

### Activity variations
Course activity items are containers that provide several interactions with the activity. They can contain

### Normal activities:
{{< callout >}}
* The activity icon
* The activity type
* The activity name
* A afterlink (showing some info from the activity)
* A activity menu (rendered from a separate template)
* Activityinfo; Open data, completion information, todo's
* Availability info: restricted ....
* The activity description (altcontent)
{{< /callout >}}

### Other activiteis do not have a link, like the label Resource
{{< callout >}}
* The description (altcontent)
* The activity menu
* Activityinfo; Open data, completion information, todo's
* Availability info: restricted ....
{{< /callout >}}

## Templates

Templates can be found in

`/admin/tool/componentlibrary/templates/activity-item.mustache`
`/admin/tool/componentlibrary/templates/activity-menu.mustache`

The SCSS for the Course activity provides the styles for the editing mode of the activity

## SCSS
{{< highlight scss>}}
$enable-rounded: true;
$activity-item-hover: theme-color-level('primary', -12) !default;
$activity-item-border: theme-color-level('primary', -2) !default;
$activity-item-color: $body-color;

.activity-item {
    @if $enable-rounded {
        @include border-radius($card-border-radius);
    }
    border: $border-width solid $border-color;
    &.editing {
        cursor: pointer;
        &:hover {
            @include alert-variant($activity-item-hover, $activity-item-border, $activity-item-color);
        }
    }
}
{{< /highlight>}}

## Basic Activity with editing

When editing mode is turned on the activities can be dragged (click and hold). On hover the activity will
highlight with a background and border color. The kebab menu will have the activity options.

{{< mustache template="tool_componentlibrary/activity-item">}}
{
    "activityname": "Hello kitty forum",
    "modname": "Forum",
    "hasname": "true",
    "editing": "true",
    "hasurl": "true",
    "icon": "http://placekitten.com/50/50",
    "menuitems": [
        {
            "name": "Add activity below",
            "action": "addbelow",
            "icon": "<i class=\"icon fa fa-plus\"></i>"
        },
        {
            "name": "Move to",
            "action": "move",
            "icon": "<i class=\"icon fa fa-chevron-right\"></i>"
        },
        {
            "name": "Delete",
            "action": "delete",
            "icon": "<i class=\"icon fa fa-trash\"></i>"
        },
        {
            "name": "Settings",
            "action": "settings",
            "icon": "<i class=\"icon fa fa-cog\"></i>"
        }
    ]
}
{{< /mustache >}}

## Activity with more info

This activity shows information in these fields

* activityname
* edit menu (3 dot menu)
* afterlink (30 unread messages)
* activityinfo (opens, done, todo)
* availability (restricted)
* description

{{< mustache template="tool_componentlibrary/activity-item">}}
{{< /mustache >}}

## Labels

In labels the label content is shown first, after this all the other info is shown.
{{< mustache template="tool_componentlibrary/activity-item">}}
{
    "editing": "true",
    "altcontent": "<h3>This is a label</h3>Suspendisse condimentum non ipsum ut ultrices. Praesent in ipsum ac ex rutrum varius gravida quis est. Vestibulum elit ante, vestibulum vel varius non, congue in velit. Vivamus posuere efficitur magna, in <a href=\"#\">faucibus lacus bibendum</a> non. Nullam non tempus tortor. Suspendisse condimentum non ipsum ut ultrices. Praesent in ipsum ac ex rutrum varius gravida quis est. Vestibulum elit ante, vestibulum vel varius non, congue in velit. Vivamus posuere efficitur magna, in faucibus lacus bibendum non. Nullam non tempus tortor Suspendisse condimentum non ipsum ut ultrices. Praesent in ipsum ac ex rutrum varius gravida quis est. Vestibulum elit ante, vestibulum vel varius non, congue in velit. Vivamus posuere efficitur magna, in faucibus lacus bibendum non. Nullam non tempus tortor",
    "activityinfo": {
        "activityname": "Course announcements",
        "hascompletion": true,
        "uservisible": true,
        "hasdates": true,
        "isautomatic": true,
        "istrackeduser": true,
        "showmanualcompletion": true,
        "activitydates": [
            {
                "label": "Opens:",
                "datestring": "6 April 2021, 6:46 PM"
            }
        ],
        "completiondetails": [
            {
                "statuscomplete": 1,
                "description": "Viewed"
            }
        ]
    },
    "menuitems": [
        {
            "name": "Add activity below",
            "action": "addbelow",
            "icon": "<i class=\"icon fa fa-plus\"></i>"
        },
        {
            "name": "Move to",
            "action": "move",
            "icon": "<i class=\"icon fa fa-chevron-right\"></i>"
        },
        {
            "name": "Delete",
            "action": "delete",
            "icon": "<i class=\"icon fa fa-trash\"></i>"
        },
        {
            "name": "Settings",
            "action": "settings",
            "icon": "<i class=\"icon fa fa-cog\"></i>"
        }
    ],
    "availability": "<div><span class=\"badge badge-info\">Restricted</span> Available from <strong>3 Dec 2029</strong></div>"
}
{{< /mustache >}}
