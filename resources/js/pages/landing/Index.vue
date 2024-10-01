<template>
  <div class="landing-container">
    <main class="landing-body">
      <section id="home" class="hero-banner mb-5">
  <div class="position-relative">
    <video autoplay muted loop class="img-fluid rounded hero-image1">
      <source src="@/assets/images/kgrounf.mp4" type="video/mp4">
      Your browser does not support the video tag.
    </video>
          <div class="hero-text position-absolute top-20 start-20 text-white p-4">
            <h1>Welcome! ยินดีต้อนรับ</h1>
            <h2>Experience the Best of Thailand</h2>
            <p>Join us for a delightful dining experience with authentic flavors.</p>
            <p>Discover the rich culinary heritage of Thailand, crafted with love and tradition.</p>
            <router-link to="/landing/thai">
              <button class="btn btn-lg btn-primary">Order Now</button>
            </router-link>
          </div>
        </div>
      </section>

      <section id="menu" class="menu-categories text-center mb-5">
        <h2 class="mb-4">Most Popular</h2>
        <div class="row justify-content-center">

          <div class="col-md-3 category-card">
            <div class="card">
              <img src="@/assets/images/somtam.jpeg" alt="Appetizers" class="img-fluid rounded-top" />
              <div class="card-body">
                <h3>Som Tam</h3>
                <h3>ส้มตำ</h3>
                <button class="btn btn-outline-primary" @click="showDescription('somtam')">View More</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 category-card">
            <div class="card">
              <img src="@/assets/images/thai.jpeg" alt="Appetizers" class="img-fluid rounded-top" />
              <div class="card-body">
                <h3>Thai Tea</h3>
                <h3>ชาเย็น</h3>
                <button class="btn btn-outline-primary" @click="showDescription('thaitea')">View More</button>
              </div>
            </div>
          </div>

          <div class="col-md-3 category-card">
            <div class="card">
              <img src="@/assets/images/P Aor.jpg" alt="Appetizers" class="img-fluid rounded-top" />
              <div class="card-body">
                <h3>P Aor</h3>
                <h3>ก๋วยเตี๋ยวพะโล้</h3>
                <button class="btn btn-outline-primary" @click="showDescription('paor')">View More</button>
              </div>
            </div>
          </div>
        </div>

        <button class="btn btn-primary mt-4" @click="toggleMenu">
          {{ showAll ? 'View Less' : 'View All Menu' }}
        </button>


        <div class="row justify-content-center" v-if="showAll">
          <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/pad.jpeg" alt="Pad Thai" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Pad Thai</h3>
                    <h3>ผัดไทย</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('padthai')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/khao.jpeg" alt="Khao Pad" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Khao Pad</h3>
                    <h3>ข้าวผัด</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('khao_pad')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/soi.jpeg" alt="Khao Soi" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Khao Soi</h3>
                    <h3>ข้าวซอย</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('khao_soi')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Tamarind Juice.jpg" alt="Tamarind Juice" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Tamarind Juice</h3>
                    <h3>น้ำมะขาม</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('tamarind_juice')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Coconut Water.jpg" alt="Coconut Water" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Coconut Water</h3>
                    <h3>น้ำมะพร้าว</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('coconut_water')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Yam Nua.jpg" alt="Yam Nua" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Yam Nua</h3>
                    <h3>ยำเนื้อ</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('yam_nua')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Gaeng Som.jpg" alt="Gaeng Som" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Gaeng Som</h3>
                    <h3>แกงส้ม</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('gaeng_som')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Khanom Jeen Nam Ya.jpg" alt="Khanom Jeen Nam Ya" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Khanom Jeen Nam Ya</h3>
                    <h3>ขนมจีนน้ำยา</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('khanom_jeen_nam_ya')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Mango Sticky Rice.jpg" alt="Mango Sticky Rice" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Mango Sticky Rice</h3>
                    <h3>ข้าวเหนียวมะม่วง</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('mango_sticky_rice')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Tako.jpg" alt="Tako" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Tako</h3>
                    <h3>ทาโก้</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('tako')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Lod Chong.jpg" alt="Lod Chong" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Lod Chong</h3>
                    <h3>ลอดช่อง</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('lod_chong')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Tom Kha Kai.jpg" alt="Tom Kha Kai" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Tom Kha Kai</h3>
                    <h3>ต้มข่าไก่</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('tom_kha_kai')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Larb.jpg" alt="Larb" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Larb</h3>
                    <h3>ลาบ</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('larb')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Pranakorn .jpg" alt="Pranakorn" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Pranakorn</h3>
                    <h3>ประนาคอน</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('pranakorn')">View More</button>
                </div>
            </div>
        </div>

        <div class="col-md-3 category-card1">
            <div class="card1">
                <img src="@/assets/images/Savoey.jpg" alt="Savoey" class="img-fluid rounded-top" />
                <div class="card-body1">
                    <h3>Savoey</h3>
                    <h3>สวัสดี</h3>
                    <button class="btn btn-outline-primary" @click="showDescription('savoey')">View More</button>
                </div>
            </div>
        </div>


        </div>


        <div v-if="visibleDescription" class="floating-card">
          <button class="close-btn" @click="visibleDescription = null">X</button>

          <h3 v-if="visibleDescription === 'somtam'">SOMTAM</h3>
          <p v-if="visibleDescription === 'somtam'">
            A spicy green papaya salad that highlights fresh, crunchy vegetables. Tossed in a zesty lime dressing, it offers a perfect balance of heat and tang. This dish is a staple of Thai cuisine, loved for its vibrant flavors and textures.
          </p>

          <h3 v-if="visibleDescription === 'thaitea'">THAI TEA</h3>
          <p v-if="visibleDescription === 'thaitea'">
            A delightful blend of strong brewed tea and creamy milk, sweetened to perfection. Its rich, aromatic flavors are enhanced with a hint of spices, making it a favorite refreshment. Served over ice, this drink is both satisfying and refreshing on a warm day.
          </p>

          <h3 v-if="visibleDescription === 'paor'">P AOR</h3>
          <p v-if="visibleDescription === 'paor'">
            A savory noodle soup that features tender meat and a rich, flavorful broth. Infused with aromatic herbs, each bowl is a comforting experience. It’s a beloved dish that captures the essence of Thai comfort food, perfect for any time of day.
          </p>

          <h3 v-if="visibleDescription === 'padthai'">Pad Thai</h3>
          <p v-if="visibleDescription === 'padthai'">
            A classic Thai stir-fried noodle dish made with rice noodles, shrimp or chicken, tofu, peanuts, and bean sprouts, flavored with tamarind and lime.
          </p>

          <h3 v-if="visibleDescription === 'khao_pad'">Khao Pad</h3>
          <p v-if="visibleDescription === 'khao_pad'">
            Thai fried rice that often includes vegetables, eggs, and your choice of protein, all seasoned with soy sauce and served with lime.
          </p>

          <h3 v-if="visibleDescription === 'khao_soi'">Khao Soi</h3>
          <p v-if="visibleDescription === 'khao_soi'">
            A northern Thai coconut curry noodle soup, featuring soft and crispy egg noodles, typically topped with pickled mustard greens and shallots.
          </p>

          <h3 v-if="visibleDescription === 'tamarind_juice'">Tamarind Juice</h3>
          <p v-if="visibleDescription === 'tamarind_juice'">
            A refreshing beverage made from tamarind pulp, sugar, and water, offering a sweet and tangy flavor that is uniquely delicious.
          </p>

          <h3 v-if="visibleDescription === 'coconut_water'">Coconut Water</h3>
          <p v-if="visibleDescription === 'coconut_water'">
            The clear liquid found inside young coconuts, known for its refreshing taste and hydrating properties, packed with electrolytes.
          </p>

          <h3 v-if="visibleDescription === 'yam_nua'">Yam Nua</h3>
          <p v-if="visibleDescription === 'yam_nua'">
            A Thai beef salad that combines grilled beef with fresh herbs, vegetables, and a spicy lime dressing, delivering a vibrant flavor.
          </p>

          <h3 v-if="visibleDescription === 'gaeng_som'">Gaeng Som</h3>
          <p v-if="visibleDescription === 'gaeng_som'">
            A sour and spicy Thai soup made with fresh ingredients like tamarind, shrimp, and various vegetables, known for its refreshing taste.
          </p>

          <h3 v-if="visibleDescription === 'khanom_jeen_nam_ya'">Khanom Jeen Nam Ya</h3>
          <p v-if="visibleDescription === 'khanom_jeen_nam_ya'">
            Rice noodles served with a rich, spicy fish curry sauce, often accompanied by fresh vegetables and herbs.
          </p>

          <h3 v-if="visibleDescription === 'mango_sticky_rice'">Mango Sticky Rice</h3>
          <p v-if="visibleDescription === 'mango_sticky_rice'">
            A popular Thai dessert made with glutinous rice, fresh mango, and drizzled with sweet coconut sauce.
          </p>

          <h3 v-if="visibleDescription === 'tako'">Tako</h3>
          <p v-if="visibleDescription === 'tako'">
            A Thai dessert made of coconut milk and pandan, steamed in small cups, often enjoyed as a snack or sweet treat.
          </p>

          <h3 v-if="visibleDescription === 'lod_chong'">Lod Chong</h3>
          <p v-if="visibleDescription === 'lod_chong'">
            A refreshing dessert made of pandan rice flour noodles in sweetened coconut milk, often served over crushed ice.
          </p>

          <h3 v-if="visibleDescription === 'tom_kha_kai'">Tom Kha Kai</h3>
          <p v-if="visibleDescription === 'tom_kha_kai'">
            A fragrant coconut soup with chicken, flavored with galangal, lemongrass, and kaffir lime leaves.
          </p>

          <h3 v-if="visibleDescription === 'larb'">Larb</h3>
          <p v-if="visibleDescription === 'larb'">
            A spicy salad made from minced meat, herbs, and spices, often served with fresh vegetables and sticky rice.
          </p>

          <h3 v-if="visibleDescription === 'pranakorn'">Pranakorn</h3>
          <p v-if="visibleDescription === 'pranakorn'">
            A traditional dish that showcases the rich flavors of Thai herbs and spices, often featuring a variety of local ingredients.
          </p>

          <h3 v-if="visibleDescription === 'savoey'">Savoey</h3>
          <p v-if="visibleDescription === 'savoey'">
            A popular Thai dish known for its savory flavors, often incorporating seafood or meat with a variety of spices.
          </p>
        </div>
      </section>

<section id="about" class="about-us text-center mb-5" style="background-color: #ffff; padding: 40px 0;">
  <div class="container">
    <h2 class="mb-4">About Us</h2>
    <div class="hero-image-container">
    <video autoplay muted loop class="img-fluid rounded hero-image11">
        <source src="@/assets/images/kgro.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>
</div>

  <p class="custom-margin">Welcome to our restaurant! Experience the vibrant essence of Thai cuisine, where authentic flavors meet culinary artistry.</p>

  <p class="custom-margin">Each dish is crafted using traditional recipes and locally sourced ingredients, ensuring fresh and bold flavors that transport you to the streets of Bangkok.</p>

  <p class="custom-margin">Dining with us is about creating memorable experiences in a warm atmosphere with attentive service, whether you’re celebrating or enjoying a casual meal.</p>

  <p class="custom-margin">We prioritize sustainability, minimizing our environmental footprint and supporting local farmers. Together, we can savor delicious food while caring for our planet.</p>

  <p class="custom-margin">Join us on this culinary journey and make lasting memories with family and friends over our signature dishes, each telling a unique story.</p>

    <<div class="opening-hours mt-5 text-center position-relative" style="padding-top: 90px;">
  <h3 class="mb-4 font-weight-bold" style="color: black;">Opening Hours</h3>
  
  <img src="@/assets/images/kgrounf.png" alt="Opening Hours Image" class="img-fluid rounded background-image" />
  
    <table class="table table-bordered">
    <thead>
      <tr>
        <th>Day</th>
        <th>Hours</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="font-weight-bold">Monday</td>
        <td>11:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Tuesday</td>
        <td>11:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Wednesday</td>
        <td>11:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Thursday</td>
        <td>11:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Friday</td>
        <td>10:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Saturday</td>
        <td>10:00 - 20:00 WIB</td>
      </tr>
      <tr>
        <td class="font-weight-bold">Sunday</td>
        <td>Closed</td>
      </tr>
    </tbody>
  </table>
</div>


<div class="team mt-5">
  <h3 class="mb-3" style="margin-top: 80px;">Meet Our Team</h3>

  <div class="row">
    <div class="col-md-4">
      <div class="card4 mb-4 shadow-sm">
        <img src="@/assets/images/300-7.jpg" alt="Chef A" class="card-img-top rounded-circle" />
        <div class="card-body">
          <h5>Chef Chada</h5>
          <p>Our head chef with over 15 years of experience in Thai cuisine, bringing authentic flavors to life.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card4 mb-4 shadow-sm">
        <img src="@/assets/images/300-23.jpg" alt="Chef B" class="card-img-top rounded-circle" />
        <div class="card-body">
          <h5>Chef Anong</h5>
          <p>Specializes in traditional Thai desserts, crafting sweet delights that captivate your palate.</p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card4 mb-4 shadow-sm">
        <img src="@/assets/images/300-1.jpg" alt="Chef C" class="card-img-top rounded-circle" />
        <div class="card-body">
          <h5>Chef Jai</h5>
          <p>Passionate about sustainability, ensuring our ingredients are fresh and locally sourced.</p>
        </div>
      </div>
    </div>
  </div>
</div>


  </div>
</section>




 
<section id="contact" class="contact-section text-center mb-5">
    <div class="container">
        <h2 class="mb-4">Get in Touch with Us</h2>
        <p class="lead mb-4">We're here to serve you! For inquiries, feedback, or reservations, please reach out.</p>
                 
        <div class="social-icons mb-4">
            <a href="https://www.instagram.com/whozviaa?igsh=emp5YnBpZzI3MzNi" target="_blank" class="btn btn-outline-danger mx-2">
                <i class="fab fa-instagram"></i> Instagram
            </a>
            <a href="https://wa.me/qr/STA2YM5YISFCF1" target="_blank" class="btn btn-outline-success mx-2">
                <i class="fab fa-whatsapp"></i> WhatsApp
            </a>
            <a href="https://facebook.com/yourrestaurant" target="_blank" class="btn btn-outline-primary mx-2">
                <i class="fab fa-facebook-f"></i> Facebook
            </a>
        </div>

        <h3 class="mt-5" style="padding-top: 80px;">Find Us Here</h3>
        <div class="map-container mb-4">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.233472303553!2d112.6518014!3d-7.2750177!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7ff3fe7d16217%3A0x1cae993101c599ae!2sJl.+Raya+Bungkal+Jl.+Bungkal+Gg.+II+No.25+B%2CRT.010%2FRW.003%2CSambikerep%2CKec.+Sambikerep%2CSurabaya%2CJawa+Timur+60217!5e0!3m2!1sen!2sus!4v1615365049658!5m2!1sen!2sus" 
                width="100%" 
                height="300" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
        
        <div class="reservation-section">
            <h3 class="mt-5">Make a Reservation</h3>
            <form @submit.prevent="submitReservation" class="mb-4">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" v-model="reservation.name" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="phone">Phone</label>
                        <input type="tel" class="form-control" id="phone" v-model="reservation.phone" required />
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" v-model="reservation.date" required />
                    </div>
                    <div class="form-group col-md-6">
                        <label for="time">Time</label>
                        <input type="time" class="form-control" id="time" v-model="reservation.time" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="guests">Number of Guests</label>
                    <input type="number" class="form-control" id="guests" v-model="reservation.guests" min="1" required />
                </div>
                <button type="submit" class="btn-reserve">Reserve Table</button>

            </form>
        </div>
    </div>
</section>

      
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';


const reservation = ref({
  name: '',
  phone: '',
  date: '',
  time: '',
  guests: 1,
});

const submitReservation = () => {
  alert(`Reservation made for ${reservation.value.guests} guests on ${reservation.value.date} at ${reservation.value.time}.`);
  reservation.value = {
    name: '',
    phone: '',
    date: '',
    time: '',
    guests: 1,
  };
};



const visibleDescription = ref<string | null>(null);
const showAll = ref(false);

const dishes = [
  { id: 'somtam', name: 'Som Tam', nameInThai: 'ส้มตำ', image: '@/assets/images/somtam.jpeg', description: 'A spicy green papaya salad that highlights fresh, crunchy vegetables. Tossed in a zesty lime dressing, it offers a perfect balance of heat and tang.' },
  { id: 'thaitea', name: 'Thai Tea', nameInThai: 'ชาเย็น', image: '@/assets/images/thai.jpeg', description: 'A delightful blend of strong brewed tea and creamy milk, sweetened to perfection.' },
  { id: 'paor', name: 'P Aor', nameInThai: 'ก๋วยเตี๋ยวพะโล้', image: '@/assets/images/P Aor.jpg', description: 'A savory noodle soup that features tender meat and a rich, flavorful broth.' },
];

const allDishes = [
  { id: 'padthai', name: 'Pad Thai', nameInThai: 'ผัดไทย', image: '@/assets/images/pad.jpeg', description: 'A classic Thai stir-fried noodle dish made with rice noodles, shrimp or chicken, tofu, peanuts, and bean sprouts.' },
  { id: 'khao_pad', name: 'Khao Pad', nameInThai: 'ข้าวผัด', image: '@/assets/images/khao.jpeg', description: 'Thai fried rice that often includes vegetables, eggs, and your choice of protein, seasoned with soy sauce.' },
  // Add more dishes here...
];

const toggleMenu = () => {
  showAll.value = !showAll.value;
};

const visibleDishes = computed(() => {
  return showAll.value ? allDishes : dishes;
});

const showDescription = (id: string) => {
  visibleDescription.value = visibleDescription.value === id ? null : id;
};

</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');


.hero-banner video {
  width: 100%;
  height: auto; /* Sesuaikan tinggi untuk responsivitas */
  object-fit: cover; /* Menjaga rasio aspek video */
}

.hero-text {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white; /* Agar teks terlihat di atas video */
  text-align: center; /* Rata tengah untuk teks */
}

.landing-container {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
}

.reservation-section {
  margin-top: 40px;
}

.hero-banner {
  margin-bottom: 30px;
}

.hero-image1 {
  max-width: 100%; /* Set a maximum width (adjust as needed) */
  display: block; /* Centering the image */
  margin: 0 auto; /* Center the image */
  height: auto; /* Maintain aspect ratio */
}

.hero-image {
  max-width: 80%; /* Set a maximum width (adjust as needed) */
  height: auto; /* Maintain aspect ratio */
  filter: brightness(70%); /* Darken the image for better text contrast */
  border-radius: 10px; /* Rounded corners */
  margin: 0 auto; /* Center the image */
  display: block; /* Centering the image */
}

.hero-text {
  border-radius: 10px;
  position: absolute;
  top: 50%;
  left: 15%; /* Adjust this value to shift further left */
  transform: translateY(-50%); /* Center vertically */
  text-align: left; /* Align text to the left */
}

.hero-text h1 {
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
  font-size: 4rem;
  margin-bottom: 5%;
}

.hero-text h2 {
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
  font-size: 2.5rem;
  margin-bottom: 10px;
  color: #000000;
}

.hero-text p {
  font-family: "Fredoka", sans-serif;
  font-size: 1.5rem;
  margin-bottom: 5px;
  color: #000000;
}

.btn-primary {
  background-color: #d9534f; /* Customize button color */
  border: none;
  padding: 12px 24px; /* Increase padding for a larger button */
  font-size: 1.25rem; /* Increase font size */
  border-radius: 0.5rem; /* Optional: Adjust border radius for a rounded effect */
  margin: 50px; /* Add spacing around the button */
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Add shadow effect */
  transition: box-shadow 0.3s; /* Smooth transition for the shadow */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
  margin-left: 0%;
  margin-top: 3%;
}

.btn-primary:hover {
  background-color: #c9302c; /* Darker shade on hover */
  box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Increase shadow on hover */
}


/* ----------------------------------------CSS MENU----------------------------------------------- */
.menu-categories {
  padding: 50px 20px;
  background-color: #ffffff; /* Light background */
  border-radius: 10px;
}

.menu-categories h2 {
  margin-bottom: 30px;
  font-size: 2.5rem;
  color: #c8102e; /* Highlight color */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}


.category-card {
  margin: 15px; /* Space between cards */
}

.card {
  border: none;
  transition: transform 0.3s, box-shadow 0.3s;
  border-radius: 10px;
  overflow: hidden; /* Rounded corners */
  background: #ffffff;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Increase shadow on hover */
  transform: scale(1.08); /* Efek zoom saat hover */
}

.card-body {
  padding: 20px;
  text-align: center;
}

.card-body h3 {
  font-size: 1.5rem;
  margin: 10px 0;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.card-body p {
  color: #55555500; /* Darker text for better readability */
}

.btn-outline-primary {
  border: 2px solid #c8102e; /* Outline color */
  color: #c8102e;
  transition: background-color 0.3s, color 0.3s;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.btn-outline-primary:hover {
  background-color: #c8102e; /* Button background on hover */
  color: white;
}

.floating-card {
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  background: rgba(255, 255, 255, 0.9); /* Adjust alpha for transparency */
  padding: 20px;
  border-radius: 5px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
  z-index: 1000; /* Ensure it appears above other content */
  display: flex; /* Enable Flexbox */
  flex-direction: column; /* Stack children vertically */
  align-items: center; /* Center children horizontally */
  justify-content: center; /* Center children vertically */
  text-align: justify; /* Justify text inside */
  font-size: 18px;
  width: 80%; /* Set the width to 80% of the viewport */
  max-width: 400px; /* Optional: Set a maximum width */
}

.floating-card h3 {
  font-size: 24px; /* Larger font for titles */
  margin-bottom: 10px; /* Space below title */
  color: #000000; /* Title color */
  text-transform: uppercase; /* Uppercase titles for emphasis */
}

.close-btn {
  background: transparent;
  border: none;
  font-size: 20px;
  cursor: pointer;
  position: absolute;
  top: 10px;
  right: 10px;
}

.floating-card p {
  margin: 10px 0;
  margin-top: 0%;
}

.category-card1 {
    margin-bottom: 30px; /* Jarak antar card */
}

.card1 {
    border: 1px solid #ddd; /* Border untuk card */
    border-radius: 8px; /* Sudut yang membulat */
    overflow: hidden; /* Menghindari gambar keluar dari batas */
    transition: transform 0.3s; /* Animasi saat hover */
    width: 90%; /* Menentukan lebar card */
    max-width: 290px; /* Lebar maksimum card */
    margin: auto; /* Memusatkan card */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Efek bayangan */
}

.card1:hover {
    transform: scale(1.05); /* Efek zoom saat hover */
}

.card-body1 {
    padding: 10px; /* Padding untuk isi card */
    text-align: center; /* Rata tengah untuk isi card */
}

.card-body1 h3 {
    margin: 1px 0; /* Jarak antar judul */
}

.card1 img {
    max-width: 100%; /* Memastikan gambar responsif */
    height: auto; /* Mengatur tinggi gambar secara otomatis */
}

.btn-outline-primary {
    margin-top: 10px; /* Jarak atas untuk button */
}

.background-container {
    position: relative;
    overflow: hidden; /* Agar konten tidak keluar dari batas */
}

.video-layer {
    position: absolute;
    top: 50%;
    left: 50%;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -1; /* Tempatkan video di belakang konten */
    transform: translate(-50%, -50%); /* Pusatkan video */
}

.content-area {
    position: relative;
    z-index: 1; /* Tempatkan konten di depan video */
    padding: 20px; /* Atur padding jika perlu */
    color: white; /* Atur warna teks untuk kontras */
}


/*--------------------------------------CSS ABOUT US-----------------------*/
.team {
    padding: 40px 0; /* Add vertical padding */
    background-color: #ffffff; /* Light background for contrast */
}

.hero-image11 {
    width: 100%; /* Lebar penuh */
    height: auto; /* Memelihara rasio aspek */
    border-radius: 10px; /* Sudut membulat */
    object-fit: cover; /* Mengisi area dengan baik */
    margin-top: -100px; /* Atur jarak atas */
    position: relative; /* Pastikan z-index berfungsi */
    z-index: -1; /* Tempatkan di belakang konten */
}

.hero-image-container {
    position: relative; /* Agar elemen lain bisa berfungsi dengan baik */
    overflow: hidden; /* Menghindari elemen keluar dari kontainer */
    z-index: 0; /* Tempatkan kontainer di depan video */
}


h3 {
    font-size: 2rem; /* Larger font size for the heading */
    color: #333; /* Dark color for the heading */
    margin-bottom: 30px; /* Space below the heading */
}

.chef-card {
    background-color: #fff; /* White background for cards */
    border-radius: 10px; /* Rounded corners */
    transition: transform 0.3s, box-shadow 0.3s; /* Smooth transition for hover effects */
    overflow: hidden; /* Ensures image and content stay within rounded corners */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
}

.chef-card:hover {
    transform: translateY(-5px); /* Lift effect on hover */
    box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15); /* Enhanced shadow on hover */
}

.pricing-section, .about-us {
  padding: 30px 0;
  background-color: #fff; /* White background for sections */
  margin: 20px 0;
  border-radius: 10px;
}

.pricing-section h2, .about-us h2 {
  margin-bottom: 20px;
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.pricing-section p, .about-us p {
  color: #555; /* Darker text for better readability */
  font-family: 'Poppins', sans-serif; /* Change to Poppins */
}

.btn-outline-primary {
  border-color: #105fc7;
  color: #000000;
}

.btn-outline-primary:hover {
  background-color: #1c84c0;
  color: white;
}

.about-us {
    background: linear-gradient(135deg, #ffffff 0%, #ffffff 100%);
    border-radius: 10px;
    
  }

  h2.mb-4 {
  font-size: 2.5rem; /* Adjust the size as needed */
  font-weight: bold; /* Optional: make it bold */
  color: #000000; /* Optional: change color for emphasis */
}

  .card {
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
  }

  .opening-hours {
  position: relative; /* Memastikan posisi relatif untuk konten di dalamnya */
  z-index: 1; /* Agar konten tetap di atas gambar */
  margin-top: 90%;
}

.background-image {
  position: absolute; /* Mengatur gambar ke posisi absolute */
  top: 0; /* Mengatur gambar agar berada di bagian atas */
  left: 0; /* Mengatur gambar agar berada di sebelah kiri */
  width: 100%; /* Membuat gambar lebar penuh */
  height: 100%; /* Membuat gambar tinggi penuh */
  object-fit: cover; /* Menjaga rasio gambar dan mengisi area */
  z-index: -1; /* Menempatkan gambar di belakang konten */
  margin-top: 2%;
}

.table {
  background-color: rgba(255, 255, 255, 0.8); /* Warna latar belakang putih dengan transparansi */
  border-radius: 10px; /* Sudut membulat untuk tabel */
  overflow: hidden; /* Menyembunyikan sudut tajam */
}




/*-------------------------------------------CSS CONTACT-------------------------------------*/
.contact-section {
    background-color: #ffffff;
    padding: 40px 0;
}

.contact-info {
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.social-icons .btn {
  font-size: 1.5rem; /* Increase font size */
  padding: 25px 35px; /* Add padding for better touch targets */
}

.social-icons .btn i {
  font-size: 2.5rem; /* Increase icon size */
  margin-right: 10px; /* More space between icon and text */
}

.social-media-header {
  margin-top: 50px; /* Adjust the value as needed */
}

.map-container {
    margin-top: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
}

.card-img-top {
    width: 50%; /* Adjusted width for the image */
    height: auto; /* Maintain aspect ratio */
    margin: 20px auto; /* Center the image */
    border: 3px solid #00000036; /* Border for the image */
}

.card-body {
    text-align: center; /* Center-align text */
    padding: 20px; /* Padding for the card body */
}

h5 {
    font-size: 1.5rem; /* Font size for the chef's name */
    color: #803e20; /* Blue color for the name */
    margin-bottom: 10px; /* Space below the name */
}

.card-body p {
    font-size: 1rem; /* Font size for description */
    color: #666; /* Gray color for description text */
    line-height: 1.5; /* Improved line height for readability */
}

.reservation-section {
  background-color: #f8f9fa; /* Light background */
  padding: 40px; /* Space around the section */
  border-radius: 8px; /* Rounded corners */
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
  margin-top: 40px; /* Space above the section */
}

.reservation-section h3 {
  font-size: 2rem; /* Larger font size for the heading */
  margin-bottom: 20px; /* Space below the heading */
  color: #343a40; /* Dark text color */
}

.form-row {
  display: flex; /* Flexbox layout for form groups */
  justify-content: space-between; /* Space between columns */
  margin-bottom: 20px; /* Space below each row */
}

.form-group {
  flex: 1; /* Take equal space */
  margin-right: 10px; /* Space between groups */
}

.form-group:last-child {
  margin-right: 0; /* Remove margin for the last group */
}

.form-control {
  border: 1px solid #ced4da; /* Light border */
  border-radius: 5px; /* Rounded edges */
  padding: 10px; /* Inner space */
  transition: border-color 0.3s; /* Smooth transition on focus */
}

.form-control:focus {
  border-color: #80bdff; /* Change border color on focus */
  outline: none; /* Remove default outline */
}

.btn-info {
  background-color: #17a2b8; /* Button background color */
  border-color: #17a2b8; /* Button border color */
  color: white; /* Text color */
  padding: 10px 20px; /* Inner space */
  border-radius: 5px; /* Rounded edges */
  margin-top: 3%
}

.btn-info:hover {
  background-color: #138496; /* Darker shade on hover */
  border-color: #117a8b; /* Darker border on hover */
  margin-top: 10%
}

.lead {
  font-size: 1.2em; /* Adjust as needed */
}
    
p {
  font-size: 1.2em; /* Adjust as needed */
}


.table {
  width: 60%; /* Table width */
  margin: 20px; /* Space around the table */
  border-collapse: collapse; /* Merge table borders */
  border-radius: 10px; /* Rounded corners */
  overflow: hidden; /* Hide overflow for rounded corners */
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
  margin-left: 20%;
}

.table th {
  background-color: #142435; /* Primary color */
  color: white; /* Text color */
  padding: 15px; /* Cell padding */
  font-size: 1.5rem; /* Font size */
}

.table td {
  text-align: center; /* Center text */
  padding: 12px; /* Cell padding */
  border-bottom: 1px solid #e9ecef; /* Border between rows */
  font-size: 1.2rem; /* Font size */

}

.table tr:hover {
  background-color: #f1f1f1; /* Highlight on hover */
}

.table tr:last-child td {
  border-bottom: none; /* Remove border from last row */
}

.btn-reserve {
  background-color: #1a217c; /* Warna hijau */
  color: white; /* Teks berwarna putih */
  border: none; /* Tanpa border */
  padding: 15px 30px; /* Padding tombol */
  font-size: 1.25rem; /* Ukuran font */
  border-radius: 5px; /* Sudut melingkar */
  transition: background-color 0.3s, transform 0.3s; /* Efek transisi */
  margin-top: 5%;
}

.btn-reserve:hover {
  background-color: #1a217c; /* Warna lebih gelap saat hover */
  transform: scale(1.05); /* Efek pembesaran */
}
</style>
