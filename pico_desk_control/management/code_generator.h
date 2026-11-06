//headder file for code generator
#ifndef CODE_GENERATOR_H
#define CODE_GENERATOR_H

#include<string>
#include<random>

namespace management{
    class Code_generator{
        private:
            std::string code_;
            
            std::random_device rd_;
            std::uniform_int_distribution<> distribution_ = std::uniform_int_distribution<int>(0, 9);
            std::mt19937 generator_ = std::mt19937(rd_());

        public:
            Code_generator();
            ~Code_generator();
            int generate_digit();
            void generate_code();
            std::string get_code();
    };
}
#endif