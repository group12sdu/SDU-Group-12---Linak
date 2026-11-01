//will generate the pairing code
#include <string>
#include <cstdint>
#include <iostream>
#include "pico/stdlib.h"
#include <random>

#include "code_generator.h"
management::Code_generator::Code_generator()
    : code_{"000000"}, rd_{}
{
    std::uniform_int_distribution<int> distribution(0, 9);
    distribution_ = distribution;
    std::mt19937 generator(rd_());
    generator_ = generator;
    //constructors
}

management::Code_generator::~Code_generator() = default; // destructor

int management::Code_generator::generate_digit(){
    return distribution_(generator_);
}

void management::Code_generator::generate_code(){
    for (int i = 0; i < 6; ++i){
        code_[i] = static_cast<char>(48 + generate_digit());
    }
}

std::string management::Code_generator::get_code(){
    return code_;
}