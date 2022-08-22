<template>
  <div>
    <div class="mb-10">
      <p class="text-2xl">
        {{ $t('campaign.latestPosts') }}
      </p>
    </div>
    <div class="mb-10">
      <p class="text-lg">Heute</p>
      <div class="flex flex-row mt-2">
        <div
          class="highlight-bg w-10 h-10 rounded-full mb-4 mr-3 overflow-hidden flex-shrink-0"
        >
          <img
            src="https://fakeimg.pl/40/"
            alt="Dummy Image"
            class="max-w-10 h-auto"
          />
        </div>
        <div class="flex flex-col flex-grow">
          <p class="mb-1">Lorem Ipsum sit dolor</p>
          <p class="highlight-text text-sm">
            Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum
            dolor sit amet.
          </p>
        </div>
      </div>
    </div>
    <div class="mb-10">
      <p class="text-lg">22. Dez.</p>
      <div class="flex flex-row mt-2">
        <div
          class="highlight-bg w-10 h-10 rounded-full mb-4 mr-3 overflow-hidden flex-shrink-0"
        >
          <img
            src="https://fakeimg.pl/40/"
            alt="Dummy Image"
            class="max-w-10 h-auto"
          />
        </div>
        <div class="flex flex-col flex-grow">
          <p class="mb-1">Lorem Ipsum sit dolor</p>
          <p class="highlight-text text-sm">
            Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum
            dolor sit amet.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
