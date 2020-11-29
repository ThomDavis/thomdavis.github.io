---
layout: post
title: Coding Challenge
---


Hello!
My name is Thom Davis.

To keep things moving I will focus on answering the request.

### Problem:
We need a way for potential employees to fill out and sign their onboarding forms from inside our own mobile app.

### Solution:
The solution was a class based system that included a blade file for each form.

These classes included an array of the questions that needed to be answered.

First I took the PDF form and ran it through an HTML converter and created a blade file from that HTML.

I then replaced each area on the blade file where an answer belongs with a variable.

Once the user had answered all the questions I then took the answers and passed them to the blade for a preview.

I then captured their signature as an image and marked on the blade file.

I was then able to produce a fully filled out form with their answers and signature for them to view or download.


### I am proud of this solution

I am so very proud of this system because it required coming up with lots of solutions, from resizing of answers
and images for each blade, to a dynamic answer system for forms that required additional answers depending on
previous answers, to multi type answer depending on the question.
However the very best part was we made it entirely server driven.

> Client: What forms do I need to fill out.
> Server: Form Bar
> Client: What is the next question for Form Bar?
> Server: Question 3: What is your Last name? Type=string, minimum length 3.
> Client: Answer to question 3 is: `foo`
> Client: What is the next question for Form Bar?

This gave us tremendous flexibility and growth. Since we had created the system on the server we could continue to add new
forms and make changes without having to release new versions of the mobile app.

From this system we easily created forms for all 50 states.

Thank you for your time,













------------------------------------------


