# Teachers.Net Profile ChatGPT Evidence Record

Status: supplied conversation evidence; not automatic project authority.

Source: `docs/process/conversation-handoff/profile/chatgpt-sources/chatgpt_teachers_net_tnet__2026-08-11.md`

Source SHA-256: `3c994d32d7cfc6d9a4c4e07e7a5eadf8ae86caaee302eacea15d356f9cd3a1f1`

Source bytes: `15036`

The complete supplied source is preserved verbatim at the path above. No
semantic filtering, chronology invention, or promotion to product authority
was performed during bootstrap.


## HANDOFF V2 OPEN/INCOMPLETE SNAPSHOT — Teachers.Net (TNET) - Profile (8/10/26)

- Session identity: `teachers-net-tnet-profile-8-10-26`
- Incorporated through: `8/11/2026, 12:51:43 PM`
- Generated: `2026-08-11T20:48:17+00:00`
- Freshness warning: newer messages may exist after this boundary.
- Classification: conversation evidence, not project authority.

### CHATGPT-bb02667b8c-000001-U — USER

teachers_net_tnet_views_8_10_26__2026-08-10.mdFilepls ingest this for context

### CHATGPT-bb02667b8c-000002-U — USER

teachers_net_tnet_teachers_net_community_3_0_desig_2026-07-29(4).mdFileteachers_net_tnet_teachers_net_community_3_0_desig_2026-07-30(5).txtDocumentteachers_net_tnet_tnet_3_0_7_29_26_2026-08-03(4).mdFileteachers_net_tnet_tnet_3_0_8_3_26_2026-08-04(4).mdFileteachers_net_tnet_views_8_4_26__2026-08-08(3).mdFileteachers_net_tnet_views_8_8_26__2026-08-10(2).mdFileteachers_net_tnet_job_center_8_8_26__2026-08-10(1).mdFileCURRENT-teachers_net_tnet_job_center_8_10_26__2026-08-10.mdFileLet's add some other chat sessions for context - not all of this will be relevant to the profile project

### CHATGPT-bb02667b8c-000003-U — USER

teachers_net_tnet_job_center_7_14_26_2026-08-10.mdFileteachers_net_tnet_job_center_7_15_26_2026-08-10.mdFileteachers_net_tnet_job_center_7_18_26_2026-08-10.mdFileteachers_net_tnet_job_center_7_23_26_2026-07-25(5).txtDocumentteachers_net_tnet_job_center_7_23_26_2026-08-10.mdFileteachers_net_tnet_job_center_7_25_26_2026-07-27(4).txtDocumentteachers_net_tnet_job_center_7_25_26_2026-08-10.mdFileteachers_net_tnet_job_center_7_28_26_2026-07-30(4).mdFileteachers_net_tnet_job_center_7_28_26_2026-08-10.mdFileteachers_net_tnet_job_center_8_1_26__2026-08-10 (1).mdFileteachers_net_tnet_job_center_8_1_26__2026-08-10.mdFileteachers_net_tnet_job_center_8_6_26__2026-08-08(3).mdFileteachers_net_tnet_job_center_8_6_26__2026-08-10.mdFileteachers_net_tnet_job_center_8_8_26__2026-08-10(2).mdFileteachers_net_tnet_job_center_8_10_26__2026-08-10 (1).mdFileteachers_net_tnet_job_center_2026-08-10.mdFilemore background context only

### CHATGPT-bb02667b8c-000004-A — ASSISTANT

I think the correct principle is:

Authentication should be almost frictionless; identity establishment should be minimal but unavoidable; richer Profile onboarding happens afterward.

And I would separate login identifier, immutable member identity, and display identity very carefully.
1. The three identities
Every member effectively has:
Email — private authentication/contact credential.
Used to sign in. Changeable after verification. Not normally public.
User ID / username — permanent human-readable Teachers.Net identity.
Chosen once, immutable, potentially exposed in URLs, attribution, historical posts, etc.
Nickname / display name — presentation identity.
What other users normally see. Changeable.
Behind those, WordPress presumably also has an immutable numeric/internal user ID. We should preserve that distinction too: the human-readable immutable username should not be our database identity/key.
Conceptually:
Internal user ID: 316
Username:         sciencegal72       ← immutable
Display name:     Ms. Garcia         ← changeable
Email:            x@example.com      ← private/changeable/verified
That separation gives us stable identity without forcing someone to permanently display the name they chose at registration.

Lowest-friction email signup
I would make first touch exceptionally small:
Create your Teachers.Net account
Email
Password
Create account
Then immediately:
Choose your Teachers.Net username
This deserves its own small step because the consequence is permanent.

Your username is permanent and may appear in places such as your profile address or activity on Teachers.Net.
For privacy, avoid using information you don't want associated publicly with your account, such as your full name, email address, birth year, school, phone number, or location.

Username
___________
Real-time availability validation.
Then something useful below:

You'll also have a display name that other members normally see. You can change your display name later.

We could optionally collect the display name here:
Display name (optional)
But I'm inclined not to.
Default the initial display name to the username and let the subsequent Profile onboarding offer:

What should people call you on Teachers.Net?

That keeps mandatory account establishment down to three pieces of information:
email + password + username.

Email verification
This deserves careful sequencing because verification can create substantial friction.
I would create the account immediately, sign the member in, and send verification in parallel.
Email + password
      ↓
Account created / authenticated
      ↓
Choose permanent username
      ↓
Continue into TNET
      │
      └── Email verification pending
Then allow ordinary low-risk exploration while verification is pending.
Only require verified email at an appropriate trust boundary—possibly before posting, messaging, creating an employer relationship, publishing content, etc. We should determine those boundaries later.
That avoids:

Create account → leave TNET → open email → find message → click → return → figure out where you were.

If verification must occur before certain actions, preserve the originating journey and return them exactly where they were afterward.
There should also be a persistent but unobtrusive:

Verify your email — Resend

until completed.

Google sign-in
Google changes authentication but should not change Teachers.Net identity establishment.
Continue with Google
       ↓
Google authentication
       ↓
TNET account created
       ↓
Choose permanent TNET username
       ↓
Continue original journey
We can use the verified email Google returns rather than requiring a redundant email-verification ceremony, assuming the authentication integration gives us appropriate verified-email assurance.
We can potentially suggest usernames based on information supplied by Google, but I'd be cautious. Automatically converting someone's Google full name/email into a permanent public-ish identifier is exactly the privacy mistake we're warning them against.
Better:

Choose your Teachers.Net username

and let them deliberately establish it.
Their Google name might be useful as a suggested display name, because that is reversible.

Apple is particularly interesting
I think Apple sign-in would be worthwhile eventually, especially because its privacy model fits what we're trying to build. But Sign in with Apple / Hide My Email means we need to architect email identity correctly.
A member might give Teachers.Net an Apple relay address rather than their actual email.
That's fine.
The rule should be:

The authentication provider's stable identity—not the literal email string—is what connects a social-login credential to the Teachers.Net account.

Otherwise email changes, Apple relay behavior, or provider changes could eventually cause account-linking trouble.
That is an implementation concern for the audit, but worth putting into the product contract now.

Returning users become simpler
Once established:
Password account
Email
Password
→ Sign in
I'd absolutely preserve your requirement that members can sign in using email + password.
Even if they initially created the account through Google/Apple, I think we should investigate whether we want to let them establish a Teachers.Net password afterward so they aren't permanently dependent on that provider.
My preference is yes:

Google/Apple should be convenient authentication methods, not ownership of the member's Teachers.Net identity.

So eventually the same account might have:
Teachers.Net account #316
├── verified email
├── password credential
├── Google credential
└── Apple credential
Any enabled credential gets them into the same account.
That is a much more durable model.

Account collision needs careful treatment
We'll inevitably encounter:
Existing TNET account: bob@example.com
Then the user clicks:
Continue with Google → Google reports bob@example.com.
We should not blindly create another account, nor should we casually link accounts solely because strings match without examining the security implications.
The desired UX is probably:

You already have a Teachers.Net account using this email.
Sign in to connect Google to your existing account.

Once authenticated, Google becomes another login method.
That's another item for the eventual identity-system audit.

Where the intent router belongs
This also makes our previous model cleaner.
For generic registration:
EMAIL/PASSWORD OR GOOGLE/APPLE
              ↓
      TNET account exists
              ↓
      Permanent username
              ↓
       Purpose router
              ↓
      Contextual onboarding
For contextual registration:
POST A JOB
     ↓
Create account
     ↓
Permanent username
     ↓
Recruiter onboarding
     ↓
Employer prerequisite
     ↓
Resume Post a Job
No purpose router because Post a Job already answered it.
Likewise:
SAVE JOB
   ↓
Create account
   ↓
Permanent username
   ↓
SAVE COMPLETES
   ↓
Optional contextual enrichment later
We shouldn't make someone complete five onboarding screens just to save something.

One place I'd challenge the initial premise
We should think carefully about whether the human-readable username truly needs to be immutable.
There are good reasons for permanence—historical attribution, URLs, recognition, abuse prevention—but we shouldn't make it immutable merely because legacy Teachers.Net did so.
A stronger architecture is:
immutable internal user ID + stable username identifier + changeable display name.
Whether the username itself is permanently immutable or merely very difficult/infrequent to change is a product decision we should explicitly make after inspecting how existing Community URLs, posts, mentions and legacy identity use it.
But regardless, if we're going to tell somebody "you can never change this," the privacy warning belongs immediately beside its creation.
That is probably the most consequential mandatory decision in the entire low-friction signup process.
So I'd lock the provisional flow as:
authenticate → establish permanent TNET identity → restore intent → contextual onboarding, with email/password always available and Google/Apple functioning as alternate credentials rather than alternate kinds of account.

### CHATGPT-bb02667b8c-000005-U — USER

That sounds good - I think Apple might not have been exactly the system I thought, I assumed it was just like Google

### CHATGPT-bb02667b8c-000006-A — ASSISTANT

Apple is similar to Google at the user-experience level—click a provider button, authenticate, return signed in—but there are a few identity/privacy differences that matter architecturally.
With Google, users commonly authenticate with a Google account whose email address is their normal address, and the provider returns a stable account identity plus email/profile data subject to scopes and settings.
With Sign in with Apple, the same general flow applies, but Apple may let the user hide their real email address and provide a private relay address instead. Also, Apple may only provide certain profile fields, such as the user's name, on the first authorization. So we shouldn't architect account linkage around "provider email equals Teachers.Net email."
For Teachers.Net, the clean abstraction is:

Google and Apple are both external authentication providers attached to the same TNET account.

So the experience can still be nearly identical:
Continue with Google
Continue with Apple
→ authenticate
→ create/find TNET account
→ choose permanent TNET username if this is a new account
→ resume the original task.
The implementation difference stays mostly behind the scenes.
I would therefore keep Apple in the candidate set, but not make it a launch requirement yet. Email/password plus Google would cover the essential architecture, and adding Apple later should be straightforward if we design provider identity correctly from the beginning.

### CHATGPT-bb02667b8c-000007-U — USER

good, sounds like we locked down a lot of the very first decisions we'd have to make

### CHATGPT-bb02667b8c-000008-A — ASSISTANT

Yes. We have enough now to establish a fairly strong first-arrival product contract before touching implementation.
The major decisions we've converged on are:


One Teachers.Net identity, not separate teacher/recruiter/job-seeker accounts.


Email + password is always a supported login path.


Account creation can begin with only email + password.


Email must ultimately be verified, but verification should create as little interruption as security permits.


Every account receives an immutable internal user ID.


A member establishes a human-readable username very early, with an explicit privacy warning if that identifier may become visible.


Display name/nickname is separate and changeable.


Google, and potentially Apple, are alternate authentication credentials, not different account types.


Social authentication does not bypass establishment of the TNET username/identity.


Entry intent is preserved. Someone clicking Post a Job should not subsequently be asked why they came to Teachers.Net.


Generic signup uses a lightweight purpose router.


Recruiting intent creates a substantially different onboarding journey.


Purpose/intent affects onboarding but does not permanently classify the person.


Employer affiliation/authority remains owned by the employer/Job Center domain rather than being stuffed into Profile.


Ordinary educator onboarding should seek useful structured context—particularly state, grades and subjects—but keep it optional and explain the benefit.


Location must distinguish declared location, contextual/search location and permissioned device location.


Browser location should never silently become permanent Profile data.


Profile facts, visibility/privacy, communications preferences, inferred interests and external-domain relationships are different kinds of state.


Behavioral signals may personalize experiences but must not silently become asserted Profile facts.


Initial privacy configuration should be simple; detailed field-level controls belong in Profile management.


The architecture should anticipate relationship-scoped visibility without prematurely committing to a "Friends" product model.


Communications preferences belong to Profile/member policy; actual notification generation/delivery belongs to a communications capability.


Initial communications choices stay simple, with progressively revealed controls and easy contextual unsubscribe later.


Onboarding is an orchestrator, not a giant form whose purpose is to "complete a profile."


The fundamental journey is authenticate → establish TNET identity → restore/resolve intent → collect only useful context → deliver the thing the member came for → progressively enrich later.


That's substantial enough that I would capture these as provisional Profile/Onboarding product principles before we go much farther. Not schema and not implementation authority yet—just a durable decision record so the next discussions build on these decisions rather than gradually mutating them.
Then I'd move to the next major question: what happens immediately after identity establishment for each of our principal entry intents—generic educator, job seeker, recruiter, Community participant, and resource/Lesson Bank user. That should expose the remaining requirements we want defined before the repository/data audit.
