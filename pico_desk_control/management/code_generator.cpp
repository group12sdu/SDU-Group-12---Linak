//will generate the pairing code
#include <string>
#include <cstdint>
#include "pico/stdlib.h"
#include <random>

#include "code_generator.h"
management::Code_generator::Code_generator()
    : code_{"000000"}
{
    std::random_device rd_{};
    std::mt19937 gen_(rd_());
    std::uniform_int_distribution<> distrib_(0, 9);
}

management::Code_generator::~Code_generator() = default; // destructor

void management::Code_generator::generate_code(){
    for (int i = 0; i < 6; ++i){
        code_[i] = distrib_(gen_);
    }
}

std::string management::Code_generator::get_code(){
    return code_;
}